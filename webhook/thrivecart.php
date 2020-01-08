<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
require 'upgrade_account.php';
require 'utilities.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];

$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
$products = array( "product-9" => "RE - BUZZ ($69)", "product-13" => "RE - IMPACT ($99)",
                   "product-12" => "RE - BUZZ ($69)", "product-14" => "RE - IMPACT ($99)",
                   "product-15" => "RE - IMPACT ($99)");
$myFile = "response.log";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
$fh = fopen($myFile, 'a');
fwrite($fh, "\n-----------------".$date."-----------------------------------\n" );

$json_data = json_encode($_REQUEST);
logit("", $json_data, "Processing: $date");

/**
 * The API endpoint and time zone.
 */
$api_timezone = new DateTimeZone('America/New_York');
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $config['THRIVECART_SECRET'] ){
 logit("INVALID", "", "Key failure: $date");
 fwrite($fh,"\nKey Failure\n");
 http_response_code(403);
 fclose($fh);
 die();
}

if( empty ( $_REQUEST['customer'] ) || empty( $_REQUEST['customer']['email'] ) )
{
  logit("INVALID","","No customer information: $date");
  http_response_code(400);
  fclose($fh);
  die();
}

$email = $_REQUEST['customer']['email'];
// Message seems to be from ThriveCart so log it.
//fwrite($fh, pretty_dump($_REQUEST));
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( empty( $_REQUEST['event'] ) ) {
   logit($email, "", "No event provided: $date");
   http_response_code(403);
   fclose($fh);
   die();
}


$event = $_REQUEST['event'];
if( !in_array($event, $events) ) {
  logit($email, "", "Invalid event( $event): $date");
  http_response_code(200);
  fclose($fh);
  die();
}


$pmf = $_REQUEST['purchase_map_flat'];
logit($email, $pmf, "purchase_map_flat: $date");


  $product = $pmf;

  if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
    $group_name = $products[$product];


  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  if( $event == "order.success")
  {
    if( account_exists($fh, $value, $email) )
    {
      if( account_isInactive($fh, $value, $email) )
      {
        // reactivate account
        reactivate_account($fh, $email, $api_endpoint, $account_id, $api_key);
      }
      else
      {
        // account is active
        if( product_isTheSame($fh, $email, $product) )
        {
          // It is a payment and just let it go.
          logit( $email, "", "Payment was received for product: $product, $date");
        }
        else
        {
          // different product, then cnange the group for the account
          $account = array(
              'password'  => 'engage123', // standard default password
            );
          $result = change_account_group($fh, $email, $api_endpoint, $account_id, $api_key,
           $group_name, $product);
          logit($email, "",  "Change product resulted in: $result, $date");
        }
      }
    }
    else
    { // account does not exist
      /**
       * The contact information to insert.
       *
       * Information will be added to your AllClients contacts!
       */
      $account = array(
        	'password'  => 'engage123',
        );
        add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $email);
        if( $product == "product-15") {
          logit($email, "", "One month free/$99 mo. added, $product");

        }
    }
  }
    else if( $event == "order.subscription_cancelled")
    {
        $result = change_account_status($fh, $api_endpoint,$account_id, $api_key, $email,0);
        logit($email,"", "Subscription_cancelled resulted in $result, $date");
    }
  }
  else
  {
    logit($email, "", "Invalid product '" . $product . "', $date");
  }

fwrite($fh,"\n------------------All Done!-----------------\n");
fclose($fh);

http_response_code(200);
//die();



?>
