<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
require 'upgrade_account.php';
require 'adjust_email_limits.php';
require 'utilities.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];

$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
$products = array( "product-9" => "RE - BUZZ ($69)", "product-12" => "RE - IMPACT ($69)",
                   "product-13" => "RE - BUZZ ($99)", "product-14" => "RE - IMPACT ($99)",
                   "product-15" => "RE - IMPACT ($99)", "product-16" => "RE - IMPACT ($99)",
                   "product-17" => "RE - IMPACT ($99)");
$email_limits = array("product-9" => 5000, "product-12" => 5000, "product-13" => 10000,
                      "product-14" => 10000, "product-15" => 10000, "product-16" => 10000,
                      "product-17" => 10000);

$date = (new DateTime('NOW'))->format("y:m:d h:i:s");

$json_data = json_encode($_REQUEST);

/**
 * The API endpoint and time zone.
 */
$api_timezone = new DateTimeZone('America/New_York');
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $config['THRIVECART_SECRET'] ){
// logit("INVALID", "", "Key failure: $date");
 http_response_code(403);
 die();
}

if( empty ( $_REQUEST['customer'] ) || empty( $_REQUEST['customer']['email'] ) )
{
//  logit("INVALID","","No customer information: $date");
  http_response_code(400);
  die();
}

$email = $_REQUEST['customer']['email'];
// Message seems to be from ThriveCart so log it.
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( empty( $_REQUEST['event'] ) ) {
//   logit($email, "", "No event provided: $date");
   http_response_code(403);
   die();
}


$event = $_REQUEST['event'];
if( !in_array($event, $events) ) {
  logit($email, "", "Invalid event- '$event'");
  http_response_code(200);
  die();
}


$pmf = (int)$_REQUEST['base_product'];



  $product = "product-$pmf";

  if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
    $group_name = $products[$product];


  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  if( $event == "order.success")
  {
    if( account_exists($value, $email) )
    {
      if( account_isInactive($value, $email) )
      {
        // reactivate account
        reactivate_account($email, $api_endpoint, $account_id, $api_key);
      }
      else
      {
        // account is active
        if( product_isTheSame($email, $product) )
        {
          // It is a payment and just let it go.
          logit( $email, $json_data, "Payment was received for product: '$product'");
        }
        else
        {
          // different product, then cnange the group for the account
          $account = array(
              'password'  => 'engage123', // standard default password
            );
          $engagemoreacct = (int)change_account_group($email, $api_endpoint, $account_id, $api_key,
           $group_name, $product);
           if( $engagemoreacct != -1 ) {
            logit($email, $json_data,  "SUCCESS: Changed product to '$product'");
            //adjust_email_limits($api_endpoint, $account_id, $api_key, $engagemoreacct, $email, $product, $email_limits);
          }
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
        $message = " with productid: $product";
        $engagemoreacct = (int)add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $email, $product);
        if( $engagemoreacct != -1 ) {
          if( $product == "product-15") { // One month free for Impact product
            $message = " - One month free/$99 mo. for product $product";
          }
          if( $product == "product-16") { // 2 months free and discounted rate
            $message = " - Special $990/yr. for $690/yr. product $product";
          }
          if( $product == "product-17") { // discounted rate
            $message = " - Special $99/mo. for $69/mo. product $product";
          }
          logit($email, $json_data, "SUCCESS: Added to account: $group_name, $message");

        //adjust_email_limits($api_endpoint, $account_id, $api_key, $engagemoreacct, $email, $product, $email_limits);
      } // end not invalid engagemoreid, so it was created.
    } // end account does not exist - create it
  }
    else if( $event == "order.subscription_cancelled")
    {
        $result = change_account_status($api_endpoint,$account_id, $api_key, $email,0);
        logit($email,$json_data, "Subscription_cancelled, result: $result");
    }
  }
  else
  {
    logit($email, $json_data, "Invalid product: $product");
  }

http_response_code(200);
//die();



?>
