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
                   "product-12" => "RE - BUZZ ($69)", "product-14" => "RE - IMPACT ($99)");
$myFile = "response.log";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
$fh = fopen($myFile, 'a');
fwrite($fh, "\n-----------------".$date."-----------------------------------\n" );

fwrite($fh, "\naccount_id = '" . $account_id . "'");
logit("", json_encode($_REQUEST), "Processing");

/**
 * The API endpoint and time zone.
 */
$api_timezone = new DateTimeZone('America/New_York');
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $config['THRIVECART_SECRET'] ){
 fwrite($fh,  "\nKey Failure\n");
 logit("", json_encode($_REQUEST), "Key failure");
 http_response_code(403);
 fclose($fh);
 die();
}

if( empty ( $_REQUEST['customer'] ) )
{
  logit("",json_encode($_REQUEST),"No customer information");
  http_response_code(400);
  fclose($fh);
  die();
} else if( empty( $_REQUEST['customer']['email'] ) )
{
  logit("", json_encode($_REQUEST),"No customer email provided");
  http_response_code(400);
  fclose($fh);
  die();
}
$email = $_REQUEST['customer']['email'];
// Message seems to be from ThriveCart so log it.
//fwrite($fh, pretty_dump($_REQUEST));
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( empty( $_REQUEST['event'] ) ) {
   fwrite($fh, "\nNo event provided.\n");
   logit("", json_encode($_REQUEST), "No event provided");
   http_response_code(403);
   fclose($fh);
   die();
}


$event = $_REQUEST['event'];
if( !in_array($event, $events) ) {
  logit("", json_encode($_REQUEST), "Invalid event");
  fwrite($fh, "\nInvalid event " . $event . "\n");
  http_response_code(200);
  fclose($fh);
  die();
}

//$charges = $order['charges'];
$data = $_REQUEST['subscriptions'];
if( empty($data) ) {
  logit("", json_encode($_REQUEST), "No subscriptions");
  fwrite($fh, "\nNo subscriptions\n" );
  http_response_code(200);
  fclose($fh);
  die();
}
$pmf = $_REQUEST['purchase_map_flat'];
logit($email, $pmf, "purchage_map_flat");
fwrite($fh, "\ndata:".json_encode($data));

$datastr = json_encode($data);
fwrite($fh, "\ndatastr: '".$datastr);
$product = "Unknown";
$pos = strpos($datastr, ':');
if ($pos !== false) {

  $product = substr($datastr, 2, $pos-3);
} // here we put other choices and set the product
  fwrite($fh,"\nThe item_identifier is '".$product."'");

  if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
    $thrivecartid = $email;
    fwrite($fh,"\nThrivecart customer_id is: ".$thrivecartid."\n");
    fwrite($fh,"\nProcessing item_identifier: '".$product."'\n");
    $group_name = $products[$product];
    fwrite($fh, "\nThe group will be: ".$group_name."\n");
    echo "\nFor $thrivecartid we are setting product to $product with name $group_name\n";


  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  if( $event == "order.success")
  {
    if( account_exists($fh, $value, $thrivecartid) )
    {
      if( account_isInactive($fh, $value, $thrivecartid) )
      {
        // reactivate account
        reactivate_account($fh, $thrivecartid, $api_endpoint, $account_id, $api_key);
      }
      else
      {
        // account is active
        if( product_isTheSame($fh, $thrivecartid, $product) )
        {
          // It is a payment and just let it go.
          fwrite($fh, "\nThis is just a payment, let it go.\n");
        }
        else
        {
          // different product, then cnange the group for the account
          $account = array(
              'password'  => 'engage123',
            );
          echo "\nProduct needs to be changed to $product\n";
          $result = change_account_group($fh, $thrivecartid, $api_endpoint, $account_id, $api_key,
           $group_name, $product);
          fwrite($fh, $result . "\n");
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
        add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $thrivecartid, $email);
    }
  }
    else if( $event == "order.subscription_cancelled")
    {
      fwrite($fh, "\nProcessing subscription_cancelled\n");
      $result = change_account_status($fh, $api_endpoint,$account_id, $api_key, $thrivecartid,0);
      fwrite($fh, $result . "\n");
    }
  }
  else
    {
      fwrite($fh, "\nInvalid product '" . $product . "'\n");
      //logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "failure - Invalid product");
    }

fwrite($fh,"\n------------------All Done!-----------------\n");
fclose($fh);

// Log this failure using logit

fwrite($fh,"\n-----------------------------------------\n");
fclose($fh);
http_response_code(200);
//die();



?>
