<?php
//
require 'mysql_common.php';
require 'thrivecart_api.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = '4K9vV0InIxP5znCa7d';
$api_key      = 'ie6n85dF826iYe5npA';

$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
$products = array( "product-9" => "RE - BUZZ ($69)", "product-X')" => "GROUP-X");
$group_name = 'RE - BUZZ ($69)';
$product_name = 'product-9';

/**
 * The API endpoint and time zone.
 */
$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';
$api_timezone = new DateTimeZone('America/New_York');
$myFile = "response.txt";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
if( $fh = fopen($myFile, 'a') ) {
fwrite($fh, "\n-----------------".$date."-----------------------------------\n");
}
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != 'IEYDASLZ8FR7' ){
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

if(  ! empty( $_REQUEST['customer'] ) ){
 fwrite($fh, "\nWe have a customer!\n");
} else {
  fwrite($fh, "No customer data!\n");
  http_response_code(418);
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
  fwrite($fh, "\nThe group will be: ".$group_name."\m");
/**
 * The contact information to insert.
 *
 * Information will be added to your AllClients contacts!
 */
$account = array(
	'email' => $_REQUEST['customer']['email'],
	'password'  => 'engage123',
  'first_name' => $_REQUEST['customer']['firstname'],
  'last_name' => $_REQUEST['customer']['lastname'],
);

/**
 * Newline character, to support browser or CLI output.
 */
$nl = php_sapi_name() === 'cli' ? "\n" : "<br>";

/**
 * Specify URL and form fields for AddContact API function.
 */
$url = $api_endpoint . 'AddAccount.aspx';
$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
	'email' => $account['email'],
	'password'  => $account['password'],
  'group' => $group_name, // Here we set the group name depending upon the product
);

$results_xml = thrivecart_api($url, $data);
/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
$accountid = (int) $results_xml->account_id;

fwrite($fh,"\nAdded account for '".$data['email']."' with $account_id '".$accountid."'\n" );

fwrite($fh,"\nThis email '".$data['email']."' can be added to the $_SESSION and saved in database, etc.\n");
// Here I write the account information using addUser in mysql_common.php
addUser($data['email'],   $thrivecartid, $account_id);
logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "success");

} else {
  logit($_REQUEST['customer']['email'],"Error: Invalid Product information, expected item_id ='".$product_name."' and got item_id='".$product."'", "failure");
  logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "failure - Invalid product");
}
// Log this failure using logit


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
