<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'add_contact.php';
require 'change_account_status.php';
require 'upgrade_account.php';
require 'utilities.php';
require 'add_contact_note.php';
require 'get_contacts.php';
//require '../smtp/notify.php';
require 'curlPost.php';
require_once 'mylib.php';

$log_file = "./mysql-errors.log";
$log = new Logging();
$log->lfile("./json-data.log");

/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];
$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';


$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund', 'order.rebill_failed', 'order.subscription_resumed');
$affiliate_events = array('affiliate.commission_refund', 'affiliate.commission_earned', 'affiliate.commission_payout');


echo "<html><head></head><body><h1>OK</h1></body></html>";
$json_data = json_encode($_REQUEST);

/**
 * The API endpoint and time zone.
 */
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
//if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $config['THRIVECART_SECRET'] ){
//logit("INVALID", $json_data, "No key supplied");
// die('Invalid request, no key supplied');
//}
$test = FALSE;
$email = 'Undefined';
if( isset( $_REQUEST['email'] ) ) {
  $email = $_REQUEST['email'];
}
// Message seems to be from ClickFunnels so log it.
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( ! $test ) {
  if( ! isset( $_REQUEST['event'] ) ) {
    logit("INVALID", $json_data, "No event provided");
     die('No event provided');
  }
  $event = $_REQUEST['event'];
} else {
  $event = 'order.subscription.cancelled';
}
switch( $event ) {
  case 'order.success':
      handleOrderSuccess($email, $api_endpoint, $account_id, $api_key, $json_data);
    break;
  case 'order.subscription.cancelled':
      $cancelling_productid = getProductId(); // e.g. 29
      $current_productid = getProductFor($email); // e.g. 13
      if( $current_productid == -1 )
      {
        $result = "Failed: Could not locate user($email) to cancel subscription";
      }
      else
      {
        $result = change_account_status($api_endpoint,$account_id, $api_key, $email,0);
        echo "The account for Email: $email was cancelled with result: $result<br />";
      }
      logit($email,$json_data, "Subscription_cancelled, result: $result");
    break;
    case 'order.subscription_resumed':
      $resumming_productid = getProductId(); // e.g. 29
      $current_productid = getProductFor($email); // e.g. 13
      if( $current_productid == -1 )
      {
        $result = "Failed: Could not locate user($email) to resume subscription";
      }
      else
      {
        $result = change_account_status($api_endpoint,$account_id, $api_key, $email,1);
        echo "The account for Email: $email was resummed with result: $result<br />";
      }
      logit($email,$json_data, "Subscription_resumed, result: $result");
      break;
  default:
      logit($email, $json_data, "Invalid event- $event");
      echo "Invalid event - $event<br />" . $email . " - " . $json_data . "<br />";
      die();
  }
  die('All Done');

  function handleOrderSuccess($email, $api_endpoint, $account_id, $api_key, $json_data) {
    $names = firstAndLastFromEmail($email);
    $first_name = $names[0];
    $last_name = $names[1];
    $from_email_address = $names[2];

  //  echo "Check if account_exists for: $from_email_address <br />";
  //  echo "json_data :  " . $json_data . "<br />";
    $product = getProductId($_REQUEST);
  //  echo "product: ". $product . "<br />";
    require 'product_data.php';
    if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product

      $group_name = getProductName($product, $from_email_address, $json_data);
  // 	echo "group_name: " . $group_name . "<br />";

      if( account_exists($from_email_address) ) {
  //      echo "It does!<br />";
        if( account_isInactive($from_email_address) )
        {
          // reactivate account
          reactivate_account($from_email_address, $api_endpoint, $account_id, $api_key);
        }
        else
        {
          // account is active
          if( product_isTheSame($from_email_address, $product) )
          {
            // It is a payment and just let it go.
            echo "Payment received for product: $product from Email: $email<br />";
            logit( $from_email_address, $json_data, "Payment was received for product: $product");
          }
          else
          {
            // different product, then cnange the group for the account
            $engagemoreacct = (int)change_account_group($from_email_address, $api_endpoint, $account_id, $api_key,
             $group_name, $product);
             if( $engagemoreacct != -1 ) {
               echo "Changed subscription to product: $product<br />";
              logit($from_email_address, $json_data,  "SUCCESS: Changed product to $product");
            }
          }
        }
      }
      else { // account does not exist
        /**
         * The contact information to insert.
         *
         * Information will be added to your AllClients contacts!
         */
        $account = array(
            'password'  => 'engage123',
          );
          $message = " with productid: $product";
          $invoiceId = getInvoiceId();
  //	echo "invoidid: " . $invoiceId . "<br />";
          $orderId = getOrderId();
  //	echo "orderid: " . $orderId . "<br />";
          $mode = getMode();
          $engagemoreacct = (int)add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $from_email_address, $product, $invoiceId, $orderId, $json_data, $mode);
  //		echo "engagemoreacct: " . $engagemoreacct . "<br />";
          if( $engagemoreacct != -1 ) {
            if( $product == "product-15") { // One month free for Impact product
              $message = " - One month free\/$99 mo. for product $product";
            }
            if( $product == "product-16") { // 2 months free and discounted rate
              $message = " - Special $990\/yr. for $690/yr. product $product";
            }
            if( $product == "product-17") { // discounted rate
              $message = " - Special $99\/mo. for $69/mo. product $product";
            }
    logit($from_email_address, $json_data, "SUCCESS: Added to account: $group_name - $message");
  	echo "SUCCESS: Added " . $from_email_address . " to account " . $group_name . " " . $message . "<br />";
        } // end not invadelid engagemoreid, so it was created.
      } // end account does not exist - create it
    } // end valid product
    else {
      logit($from_email_address, $json_data, "NOT TRACKED: $product is not tracked by this webhook.");
      echo "<br /><h3>NOT TRACKED: $product is not tracked by this webhook.</h3>";
      echo "<br />for " . $from_email_address . ", " . $json_data. "<br />";
    }
  }

?>
