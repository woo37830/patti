<?php
//
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'cancel_account.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $_ENV['MSG_USER'];
$api_key      = $_ENV['MSG_PASSWORD'];

$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
$products = array( "product-9" => "RE - BUZZ ($69)", "product-X')" => "GROUP-X");

$myFile = "response.txt";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
if( $fh = fopen($myFile, 'a') ) {
fwrite($fh, "\n-----------------".$date."-----------------------------------\n");
}

/**
 * The API endpoint and time zone.
 */
$api_timezone = new DateTimeZone('America/New_York');
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $_ENV['THRIVECART_SECRET'] ){
 fwrite($fh, "Key Failure\n");
 http_response_code(403);
 fclose($fh);
 die();
}

// Message seems to be from ThriveCart so log it.
//fwrite($fh, pretty_dump($_REQUEST));
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( empty( $_REQUEST['event'] ) ) {
   fwrite($fh," No event provided. \n");
   http_response_code(403);
   fclose($fh);
   die();
}


$event = $_REQUEST['event'];
if( !in_array($event, $events) ) {
  logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "Invalid event");
  http_response_code(200);
  fclose($fh);
  die();
}

//$charges = $order['charges'];
$data = $_REQUEST['subscriptions'];
if( empty($data) ) {
  logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "No subscriptions");
  fclose($fh);
  die();
}
fwrite($fh,"data:".json_encode($data)."\n");

$datastr = json_encode($data);
fwrite($fh, "datastr: '".$datastr."\n");
$product = "Unknown";
$pos = strpos($datastr, ':');
if ($pos !== false) {

  $product = substr($datastr, 2, $pos-3);
} // here we put other choices and set the product
  fwrite($fh,"\nThe item_identifier is '".$product."'\n");

if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
  $thrivecartid = $_REQUEST['customer_id'];
  fwrite($fh,"\nThrivecart customer_id is: ".$thrivecartid."\n");
  fwrite($fh,"\nProcessing item_identifier: '".$product."'\n");
  $group_name = $products[$product];
  fwrite($fh, "\nThe group will be: ".$group_name."\n");


/**
 * Newline character, to support browser or CLI output.
 */
$nl = php_sapi_name() === 'cli' ? "\n" : "<br>";

$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';
$email = $REQUEST['customer']['email'];

if( $event == "order.success")
{
  if( account_exists($fh) )
  {
    if( account_isInactive($fh) )
    {
    // reactivate account
    reactivate_account($fh);
    }
    else
    {
      // account is active
      if( product_isTheSame($fh) )
      {
        // It is a payment and just let it go.
      }
      else
      {
        // different product, then cnange the group for the account
        change_account_group($fh);
      }
    }
  }
  else
  {
    /**
     * The contact information to insert.
     *
     * Information will be added to your AllClients contacts!
     */
    $account = array(
      	'email' => $email,
      	'password'  => 'engage123',
      );
      add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $thrivecartid, $email);
  }
  else if( $event == "order.subscription_cancelled")
    {
      fwrite($fh, "\nProcessing subscription_cancelled\n");
      $result = cancel_account($api_endpoint,$account_id, $api_key, $thrivecartid);
      fwrite($fh, $resut . "\n");
    }
}
else
{
  logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "failure - Invalid product");
}
// Log this failure using logit

function account_exists($fh) {
  fwrite($fh, "\nProcessing order.success\n");
}
function account_isInactive($fh) {
  fwrite($fh, "\nAccount already exists\n");
}
function reactive_account($fh) {
  fwrite($fh, "\nAccount needs to be re-activated\n");
}
function change_account_group($fh) {
  fwrite($fh, "\nDifferent product, so change group\n");
}
function product_isTheSame($fh) {
  fwrite($fh, "\nAccount is active, and event is for payment to same product\n");
}
function change_account_group($fh) {
  fwrite($fh, "\nChange account group\n");
}
function pretty_dump($mixed = null) {
  ob_start();
  echo json_encode($_REQUEST, JSON_PRETTY_PRINT);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

//file_put_contents($file, var_dump_ret($_REQUEST));
function dump_response($msg = null) {
  $eFile = "error.txt";
  $date = (new DateTime('NOW'))->format("y:m:d h:i:s");
  if( $err = fopen($eFile, 'a') ) {
	  fwrite($err, "\n-----------------".$date."-----------------------------------\n");
	  fwrite($err, $msg."\n");
	  fwrite($err,"JSON DUMP\n");
	  fwrite($err, pretty_dump($_REQUEST));
	  fwrite($err,"\nEND OF DUMP\n");
	  fclose($err);
	}
  return;
}
fwrite($fh,"\n-----------------------------------------\n");
fclose($fh);
http_response_code(200);
//die();



?>
