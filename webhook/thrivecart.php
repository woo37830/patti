<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
require 'upgrade_account.php';
require 'utilities.php';
require 'add_contact_note.php';
require 'get_contacts.php';
require '../smtp/notify.php';
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


$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund', 'order.rebill_failed');
$affiliate_events = array('affiliate.commission_refund', 'affiliate.commission_earned', 'affiliate.commission_payout');


echo "<html><head></head><body><h1>OK</h1></body></html>";
$json_data = json_encode($_REQUEST);

/**
 * The API endpoint and time zone.
 */
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
/*if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $config['THRIVECART_SECRET'] ){
logit("INVALID", $json_data, "No key supplied");
 die('Invalid request, no key supplied');
}
*/
$email = 'Undefined';
if( isset( $_REQUEST['event'] ) ) {
  $event = $_REQUEST['event'];
  if( isset($_REQUEST['customer'] ) ) {
    $email = $_REQUEST['customer']['email'];
  }
}
// Message seems to be from ThriveCart so log it.
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( empty( $event ) ) {
  logit("INVALID", $json_data, "No event provided");
   die('No event provided');
}
$log->lwrite("$email,$json_data");

//$email = get_email_from_rfc_email($email);
switch( $event ) {
  case 'order.success':
    handleOrderSuccess($email, $api_endpoint, $account_id, $api_key, $json_data);
    break;
  case 'order.subscription_payment':
    handleSubscriptionPayment($email, $api_endpoint, $account_id, $api_key, $json_data);
    break;
  case 'order.rebill_failed':
    $result = change_account_status($api_endpoint,$account_id, $api_key, $email,0);
    logit($email,$json_data, "order.rebill_failed, cancelled account, result: $result");
    break;
  case 'order.refund':
      logit($email, $json_data, "order.refund");
      $result = change_account_status($api_endpoint,$account_id, $api_key, $email,0);
      logit($email,$json_data, "Subscription_cancelled because of order.refund, result: $result");
      $theMessage = "Account $email has cancelled!";
  //    sendNotification($email,'Cancellation Notice',$theMessage);
      echo "Received order.refund. result = $result<br />" . $email . " - " . $json_data . "<br />";
      break;
  case 'order.subscription_cancelled':
    $result = change_account_status($api_endpoint,$account_id, $api_key, $email,0);
    logit($email,$json_data, "Subscription_cancelled, result: $result");
    $theMessage = "Account $email has cancelled!";
//    sendNotification($email,'Cancellation Notice',$theMessage);
    echo "Received order.subscription_cancelled. result = $result<br />" . $email . " - " . $json_data . "<br />";
    break;
  case 'affiliate.commission_refund':
    logit($email, $json_data, "affiliate.commission_refund");
    echo "Received affiliate.commission_refund<br />" . $email . " - " . $json_data . "<br />";
    break;
  case 'affiliate.commission_earned':
    logit($email, $json_data, "affiliate.commission_earned");
    echo "Received affiliate.commission_earned<br />" . $email . " - " . $json_data . "<br />";
    break;
  case 'affiliate.commission_payout':
    logit($email, $json_data, "affiliate.commission_payout");
    echo "Received affiliate.commission_payout<br />" . $email . " - " . $json_data . "<br />";
    break;
  default:
    logit($email, $json_data, "Invalid event- $event");
    echo "Invalid event - $event<br />" . $email . " - " . $json_data . "<br />";
    die();
}
  //echo "Received event: $event with email: $email</br/>";
  echo "<br /><hr />";
  require 'git-info.php';
  die('All Done');

function handleSubscriptionPayment($email, $api_endpoint, $account_id, $api_key, $json_data) {
//  echo "Check if account_exists for: $email <br />";
//  echo "json_data :  " . $json_data . "<br />";
  $product = getProductId($_REQUEST);
//  echo "product: ". $product . "<br />";
  require 'product_data.php';
/*  if( $product != 'product-29' )
  {
    if( $product != 'product-24' ) {
    logit($email, $json_data, "order.subscription_payment for " . $product);
    echo "Received order.subscription_payment<br />" . $email . " - " . $json_data . "<br />";
    return;
    }
  }
  */
// We are here because we are looking at payment for product-29 or product-24.
    if( account_exists($email) ) {
//      echo "It does!<br />";
      if( account_isInactive($email) )
      {
//        echo "order.subscription_payment for inactive account<br>";
      //  logit($email, $json_data, "FAILURE: order.subscription_payment for inactive account " . $product);
        $result = change_account_status($api_endpoint,$account_id, $api_key, $email,1);
        logit($email,$json_data, "Subscription_reactivated, result: $result");

        return;
      }
      else
      {
        // account is active and the product on record is product-29 or product-24
        // then we want to change it to product-13
        if( product_isTheSame($email, $product) )
        { // i.e. product-29 or product-24 is the current product for the $email
          // different product, then cnange the group for the account
          $product = 'product-13'; // change it to product-13
          $group_name = getProductName($product, $email, $json_data);
  //        echo "group_name: " . $group_name . "<br />";

          $engagemoreacct = (int)change_account_group($email, $api_endpoint, $account_id, $api_key,
           $group_name, $product);
           if( $engagemoreacct != -1 ) {
             echo "Changed subscription to product: $product<br />";
            logit($email, $json_data,  "SUCCESS: Changed product to $product");
          }
        } else { // This should not happen as it means the product being paid for is product-29
          // AND the user already has product-29 recorded.
          // This should have been changed the first subscription payment to product-13
          if( $product == 'product-24' || $product == 'product-29' )
          {
            $today = date("D M j G:i:s T Y");
            $from = "jwooten37830@icloud.com";
            $to = "log_notes@gmail.com";
            $messageId = $today;
            $subject = "Failure: Changing product";
            $message = "Failure: $product on record should have already been changed for $email";
            $attachmentLog = $json_data;
            $postArray = "The complete <a >data</a> received in the request";
              $result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
            echo "Failure: $product on record should have already been changed for $email<br />";
            logit($email, $json_data,  "FAILURE: $product should have already been changed!");
          } else {
            $group_name = getProductName($product, $email, $json_data);
            $engagemoreacct = (int)change_account_group($email, $api_endpoint, $account_id, $api_key,
            $group_name, $product);
            if( $engagemoreacct != -1 ) {
              echo "Changed subscription to product: $product<br />";
              logit($email, $json_data,  "SUCCESS: Changed product to $product");
            }
          }
         return;
        }
      }
    }
    else { // account does not exist
      echo "FAILURE: the account $email does not exist<br>";
      logit($email, $json_data,  "FAILURE: Changing product to $product because account does not exist.");
      return;
    }
    return;
}


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
          echo "Payment received for product: $product<br />";
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
