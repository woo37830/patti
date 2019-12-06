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
fwrite($fh,"account_id = '" . $account_id . "'\n");
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
  $thrivecartid = (int)$_REQUEST['customer_id'];
  fwrite($fh,"\nThrivecart customer_id is: ".$thrivecartid."\n");
  fwrite($fh,"\nProcessing item_identifier: '".$product."'\n");
  $group_name = $products[$product];
  fwrite($fh, "\nThe group will be: ".$group_name."\n");

$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

if( $event == "order.success")
{
  if( account_exists($fh, $value, $thrivecartid) )
  {
    if( account_isInactive($fh, $value, $thrivecartid) )
    {
      // reactivate account
      reactivate_account($fh, $thrivecartid);
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
            'email' => 'jwooten37830@icloud.com',
            'password'  => 'engage123',
          );

        $result = change_account_group($fh, $thrivecartid, $api_endpoint, $account_id, $api_key,
         $group_name, $product, $email);
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
      	'email' => 'jwooten37830@icloud.com',
      	'password'  => 'engage123',
      );
      add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $thrivecartid, $email);
  }
}
  else if( $event == "order.subscription_cancelled")
  {
    fwrite($fh, "\nProcessing subscription_cancelled\n");
    $result = cancel_account($api_endpoint,$account_id, $api_key, $thrivecartid);
    fwrite($fh, $result . "\n");
  }
}
else
  {
    fwrite($fh, "\nInvalid product '" . $product . "'\n");
    //logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "failure - Invalid product");
  }

fwrite($fh,"\n------------------All Done!-----------------\n");


// Log this failure using logit

function account_exists($fh, $value, $thrivecartid) {
  fwrite($fh, "\nProcessing order.success\n");
  //return $value['account_exists'];
  $acct_id = getAccountId( $thrivecartid );
  return $acct_id != -1;
}
function account_isInactive($fh, $value, $thrivecartid) {
  fwrite($fh, "\nChecking account status\n");
  $id = getAccountId($thrivecartid);
  echo "Obtained engagemoreid = '" . $id . "'\n";

  $saved_status = getStatusFor($id);

  //  return $value['account_isInactive'];
  fwrite($fh, "\ngetStatusFor returned '" . $saved_status . "'\n");
  return $saved_status == 'inactive';
}
function reactivate_account($fh, $thrivecartid) {
  $accountid = getAccountId( $thrivecartid );
  fwrite($fh, "\nUse SetStatus API to set status of EngagemoreCRM account to active\n");
  updateAccountStatus($accountid, "active");
}
function change_account_group($fh, $thrivecartid, $api_endpoint, $account_id, $api_key, $group_name, $productid, $email) {
  $accountid = getAccountId( $thrivecartid );
  fwrite($fh, "\nUse SetAccountGroup API to set account group of EngagemoreCRM account\n");
  upgrade_account($api_endpoint, $account_id, $api_key, $accountid,
   $group_name, $productid, $email);
}
function product_isTheSame($fh, $thrivecartid, $product) {
  $saved_product = getProductFor( $thrivecartid );
  return $product == $saved_product;
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
