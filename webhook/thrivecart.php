<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
require 'upgrade_account.php';
require 'utilities.php';
require '../smtp/notify.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];
$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';


$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
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
switch( $event ) {
  case 'order.success':
    logit($email,$json_data,"Received order.success");
    handleOrderSuccess($email, $api_endpoint, $account_id, $api_key, $json_data);
    echo "Received order.success<br />" . $email . " - " . $json_data . "<br />";
    break;
  case 'order.subscription_payment':
    handleSubscriptionPayment($email, $api_endpoint, $account_id, $api_key, $json_data);
    break;
  case 'order.subscription_cancelled':
    $result = change_account_status($api_endpoint,$account_id, $api_key, $email,0);
    logit($email,$json_data, "Subscription_cancelled, result: $result");
    $theMessage = "Account $email has cancelled!";
    sendNotification($email,'Cancellation Notice',$theMessage);
    echo "Received order.subscription_cancelled. result = $result<br />" . $email . " - " . $json_data . "<br />";
    break;
  case 'order.refund':
    logit($email, $json_data, "order.refund");
    echo "Received order.refund<br />" . $email . " - " . $json_data . "<br />";
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
  echo "Check if account_exists for: $email <br />";
  echo "json_data :  " . $json_data . "<br />";
  $product = getProductId($_REQUEST);
  echo "product: ". $product . "<br />";
  require 'product_data.php';
  if( $product != 'product-29' )
  {
    if( $product != 'product-24' ) {
    logit($email, $json_data, "order.subscription_payment for " . $product);
    echo "Received order.subscription_payment<br />" . $email . " - " . $json_data . "<br />";
    return;
    }	
  }
// We are here because we are looking at payment for product-29 or product-24.
    if( account_exists($email) ) {
      echo "It does!<br />";
      if( account_isInactive($email) )
      {
        echo "order.subscription_payment for inactive account<br>";
        logit($email, $json_data, "FAILURE: order.subscription_payment for inactive account " . $product);
      // reactivate account
      //  reactivate_account($email, $api_endpoint, $account_id, $api_key);
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
          echo "group_name: " . $group_name . "<br />";

          $engagemoreacct = (int)change_account_group($email, $api_endpoint, $account_id, $api_key,
           $group_name, $product);
           if( $engagemoreacct != -1 ) {
             echo "Changed subscription to product: $product<br />";
            logit($email, $json_data,  "SUCCESS: Changed product to $product");
          }
        } else { // This should not happen as it means the product being paid for is product-29
          // AND the user already has product-29 recorded.
          // This should have been changed the first subscription payment to produt-13
          echo "Failure: $product on record should have been changed for $email<br />";
         logit($email, $json_data,  "FAILURE: $product should have already been changed!");
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
  echo "Check if account_exists for: $email <br />";
  echo "json_data :  " . $json_data . "<br />";
  $product = getProductId($_REQUEST);
  echo "product: ". $product . "<br />";
  require 'product_data.php';
  if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product

    $group_name = getProductName($product, $email, $json_data);
 	echo "group_name: " . $group_name . "<br />";

    if( account_exists($email) ) {
      echo "It does!<br />";
      if( account_isInactive($email) )
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
          echo "Payment received for product: $product<br />";
          logit( $email, $json_data, "Payment was received for product: $product");
        }
        else
        {
          // different product, then cnange the group for the account
          $engagemoreacct = (int)change_account_group($email, $api_endpoint, $account_id, $api_key,
           $group_name, $product);
           if( $engagemoreacct != -1 ) {
             echo "Changed subscription to product: $product<br />";
            logit($email, $json_data,  "SUCCESS: Changed product to $product");
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
	echo "invoidid: " . $invoiceId . "<br />";
        $orderId = getOrderId();
	echo "orderid: " . $orderId . "<br />";
        $engagemoreacct = (int)add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $email, $product, $invoiceId, $orderId, $json_data);
		echo "engagemoreacct: " . $engagemoreacct . "<br />";
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
	echo "SUCCESS: Added " . $email . " to account " . $group_name . " " . $message . "<br />";
      } // end not invadelid engagemoreid, so it was created.
    } // end account does not exist - create it
  } // end valid product
  else {
    logit($email, $json_data, "NOT TRACKED: $product is not tracked by this webhook.");
    echo "<br /><h3>NOT TRACKED: $product is not tracked by this webhook.</h3>";
    echo "<br />for " . $email . ", " . $json_data. "<br />";
  }
}




?>
