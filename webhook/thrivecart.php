<?php
//
require('post_api_url');
/**
 * AllClients Account ID and API Key.
 */
$account_id   = '4K9vV0InIxP5znCa7d';
$api_key      = 'ie6n85dF826iYe5npA';
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
if(  ! empty( $_REQUEST['customer'] ) ){
 fwrite($fh, "\nWe have a customer order!\n");
} else {
  fwrite($fh, "No customer data!\n");
  http_response_code(418);
  fclose($fh);
  die();
}
if( empty($_REQUEST['order']) ) {
  fwrite($fh,"No order data provided");
  dump_response("No order data provided");
  fclose($fh);
  die();
}
$order = $_REQUEST['order'];
if( empty($order['charges'] ) ) {
  fwrite($fh,"No charges data provided");
  dump_response("No charge data provided");
  fclose($fh);
  die();
}
$charges = $order['charges'];
$data = $_REQUEST['subscriptions'];
fwrite($fh,"data:".$data."\n");
if( empty($data) ) {
  fwrite($fh,"No subscription provided");
  dump_response("No subscription provided");
  fclose($fh);
  die();
}
$item_id = array_keys($data)[0];
  fwrite($fh,"\nThe item_identifier is '%s'\n",$item_id);

if( $product_name === $item_id ) { // Here is where we check that we have the correct product
  fwrite($fh,"\nProcessing item_identifier: '%s'\n",$item_id);
/**
 * The contact information to insert.
 *
 * Information will be added to your AllClients contacts!
 */
$account = array(
	'email' => $_REQUEST['customer']['email'],
	'password'  => '123123',
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
  'group' => $group_name,
);

/**
 * Exit if contact information is not specified.
 */
if (empty($data['email']) || empty($data['password'])) {
	fwrite($fh,"\nEmail and password must be specified at top of file to run.\n");
	fclose($fh);
  http_response_code(422);
  exit;
}

/**
 * Insert the account and get the response as XML string:
 *
 *   <?xml version="1.0"?>
 *   <results>
 *     <message>Success</message>
 *     <contactid>15631</contactid>
 *   </results>
 *
 * @var string $contacts_xml_string
 */
$result_xml_string = post_api_url($url, $data);

/**
 * SimpleXML will create an object representation of the XML API response. If
 * the XML is invalid, simplexml_load_string will return false.
 *
 * @var SimpleXMLElement $results_xml
 */
$results_xml = simplexml_load_string($result_xml_string);
if ($results_xml === false) {
	fwrite($fh,"\nError parsing XML\n");
  fclose($fh);
  http_response_code(400);
	exit;
}
fwrite($fh,"\ncURL command has been issued and results received\n");
/**
 * If an API error has occurred, the results object will contain a child 'error'
 * SimpleXMLElement parsed from the error response:
 *
 *   <?xml version="1.0"?>
 *   <results>
 *     <error>Authentication failed</error>
 *   </results>
 */
if (isset($results_xml->error)) {
	fwrite($fh,"\nAllClients API returned an error: %s\n", $results_xml->error);
	fclose($fh);
  http_response_code(400);
  exit;
}

/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
$accountid = (int) $results_xml->account_id;

fwrite($fh,"\nAdded account for '%s' with $account_id '%d'\n", $data['email'], $accountid);

fwrite($fh,"\nThis email '%s' can be added to the $_SESSION and saved in database, etc.\n", $data['email']);

} else {
  fwrite($fh,"\nError: Invalid Product information, product_name='%s' and item_id='%s'\n",$product_name, $item_id);
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
