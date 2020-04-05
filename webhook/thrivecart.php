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

$email_limits = array("product-9" => 5000, "product-12" => 5000, "product-13" => 10000,
                      "product-14" => 10000, "product-15" => 10000, "product-16" => 10000,
                      "product-17" => 10000);
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
    echo "Received order.success<br />" . $email . " - " . $json_data . "<br />";
    handleOrderSuccess($email, $api_endpoint, $account_id, $api_key, $json_data);
    break;
  case 'order.subscription_payment':
    logit($email, $json_data, "order.subscription_payment");
    echo "Received order.subscription_payment<br />" . $email . " - " . $json_data . "<br />";
    break;
  case 'order.subscription_cancelled':
    $result = change_account_status($api_endpoint,$account_id, $api_key, $email,0);
    logit($email,$json_data, "Subscription_cancelled, result: $result");
    $theMessage = "Account $email has cancelled!";
    sendNotification('jwooten37830@icloud.com','Cancellation Notice',$theMessage);
    echo "Received order.subscription_cancelled. result = $result<br />" . $email . " - " . $json_data . "<br />";
    break;
  case 'order.refund':
  echo "Received order.refund<br />" . $email . " - " . $json_data . "<br />";
    logit($email, $json_data, "order.refund");
    break;
  case 'affiliate.commission_refund':
    echo "Received affiliate.commission_refund<br />" . $email . " - " . $json_data . "<br />";
    logit($email, $json_data, "affiliate.commission_refund");
    break;
  case 'affiliate.commission_earned':
    echo "Received affiliate.commission_earned<br />" . $email . " - " . $json_data . "<br />";
    logit($email, $json_data, "affiliate.commission_earned");
    break;
  case 'affiliate.commission_payout':
    echo "Received affiliate.commission_payout<br />" . $email . " - " . $json_data . "<br />";
    logit($email, $json_data, "affiliate.commission_payout");
    break;
  default:
    echo "Invalid event - $event<br />" . $email . " - " . $json_data . "<br />";
    logit($email, $json_data, "Invalid event- $event");
    die();
}
  //echo "Received event: $event with email: $email</br/>";
  echo "<br /><hr />";
  require 'git-info.php';
  die('All Done');



function handleOrderSuccess($email, $api_endpoint, $account_id, $api_key, $json_data) {
  //echo "Check if account_exists for: $email <br />";
  //echo "json_data :  " . $json_data . "<br />";
  $product = getProductId($_REQUEST);
  require 'product_data.php';
  if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product

    $group_name = getProductName($product, $email, $json_data);

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
            //adjust_email_limits($api_endpoint, $account_id, $api_key, $engagemoreacct, $email, $product, $email_limits);
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
        $orderId = getOrderId();
        $engagemoreacct = (int)add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $email, $product, $invoiceId, $orderId, $json_data);
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
  } // end valid product
  else {
    logit($email, $json_data, "NOT TRACKED: $product is not tracked by this webhook.");
    echo "<br /><h3>NOT TRACKED: $product is not tracked by this webhook.</h3>";
    echo "<br />for " . $email . ", " . $json_data. "<br />";
  }
}




?>
