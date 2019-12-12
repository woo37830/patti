<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
require 'upgrade_account.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];

$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
$products = array( "product-9" => "RE - BUZZ ($69)", "product-X')" => "GROUP-X");

$myFile = "response.log";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
$fh = fopen($myFile, 'a');
fwrite($fh, "\n-----------------".$date."-----------------------------------\n" );

fwrite($fh, "\naccount_id = '" . $account_id . "'");
/**
 * The API endpoint and time zone.
 */
$api_timezone = new DateTimeZone('America/New_York');
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $config['THRIVECART_SECRET'] ){
 fwrite($fh,  "\nKey Failure\n");
 http_response_code(403);
 fclose($fh);
 die();
}

// Message seems to be from ThriveCart so log it.
//fwrite($fh, pretty_dump($_REQUEST));
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( empty( $_REQUEST['event'] ) ) {
   fwrite($fh, "\nNo event provided.\n");
   http_response_code(403);
   fclose($fh);
   die();
}


$event = $_REQUEST['event'];
if( !in_array($event, $events) ) {
  logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "Invalid event");
  fwrite($fh, "\nInvalid event " . $event . "\n");
  http_response_code(200);
  fclose($fh);
  die();
}

//$charges = $order['charges'];
$data = $_REQUEST['subscriptions'];
if( empty($data) ) {
  logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "No subscriptions");
  fwrite($fh, "\nNo subscriptions\n" );
  fclose($fh);
  die();
}
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
  $thrivecartid = (int)$_REQUEST['customer_id'];
  fwrite($fh,"\nThrivecart customer_id is: ".$thrivecartid);
  fwrite($fh,"\nProcessing item_identifier: '".$product."'");
  $group_name = $products[$product];
  fwrite($fh, "\nThe group will be: ".$group_name);

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
        fwrite($fh, "\nThis is just a payment, let it go.");
      }
      else
      {
        // different product, then cnange the group for the account

        $result = change_account_group($fh, $thrivecartid, $api_endpoint, $account_id, $api_key,
         $group_name, $product, $email);
        fwrite($fh, "\n" . $result );
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
      	'email' => $_REQUEST['customer']['email'],
      	'password'  => 'engage123',
      );
      add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $thrivecartid, $email);
  }
}
  else if( $event == "order.subscription_cancelled")
  {
    fwrite($fh, "\nProcessing subscription_cancelled");
    $result = change_account_status($fh, $api_endpoint,$account_id, $api_key, $thrivecartid,0);
    fwrite($fh, "\n" . $result );
  }
}
else
  {
    fwrite($fh, "\nInvalid product '" . $product . "'");
    //logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "failure - Invalid product");
  }

fwrite($fh,"\n------------------All Done!-----------------\n");
fclose($fh);

// Log this failure using logit

function account_exists($fh, $value, $thrivecartid) {
  fwrite($fh, "\nProcessing order.success");
  //return $value['account_exists'];
  $acct_id = getAccountId( $thrivecartid );
  return $acct_id != -1;
}
function account_isInactive($fh, $value, $thrivecartid) {
  fwrite($fh, "\nChecking account status");
  $id = getAccountId($thrivecartid);
  echo "Obtained engagemoreid = '" . $id . "'";

  $saved_status = getStatusFor($id);

  //  return $value['account_isInactive'];
  fwrite($fh, "\ngetStatusFor returned '" . $saved_status . "'");
  return $saved_status == 'inactive';
}
function reactivate_account($fh, $thrivecartid, $api_endpoint, $account_id, $api_key) {
  $accountid = getAccountId( $thrivecartid );
  change_account_status($fh, $api_endpoint,$account_id, $api_key, $thrivecartid,1);
}
function change_account_group($fh, $thrivecartid, $api_endpoint, $account_id, $api_key, $group_name, $productid, $email) {
  $accountid = getAccountId( $thrivecartid );
  fwrite($fh, "\nUse SetAccountGroup API to set account group of EngagemoreCRM account");
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
