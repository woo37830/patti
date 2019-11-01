<?php
//

/**
 * AllClients Account ID and API Key.
 */
$account_id   = '4K9vV0InIxP5znCa7d';
$api_key      = 'ie6n85dF826iYe5npA';
$group_name = 'RE - BUZZ ($69)';
$product_name = '9';
/**
 * The API endpoint and time zone.
 */
$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';
$api_timezone = new DateTimeZone('America/New_York');
$myFile = "response.txt";
$fh = fopen($myFile, 'w');
fwrite($fh, "Started\n");
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != 'IEYDASLZ8FR7' ){
 dump_response("Key Failure");
 http_response_code(403);
 fclose($fh);
 die();
}

fwrite($fh,"Check for event and customer\n");
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( $_REQUEST['event'] == "order.success" && ! empty( $_REQUEST['customer'] ) ){
  //process_response();
} else {
  dump_response("Invalid event.{$nl}");
  http_response_code(418);
  fclose($fh);
  die();
  //dump_response("Invalid event");
}

if( !empty($_REQUEST['base_product'] ) && $_REQUEST['base_product'] == $product_name ) {
fwrite($fh,"Have item_identifier '%s'\n",$product_name);

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
  'mailmerge_fullname' => $_REQUEST['customer']['name'],
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
	fwrite($fh,"Email and password must be specified at top of file to run.{$nl}");
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
	fwrite($fh,"Error parsing XML{$nl}");
  fclose($fh);
  http_response_code(400);
	exit;
}

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
	fwrite($fh,"AllClients API returned an error: %s{$nl}", $results_xml->error);
	fclose($fh);
  http_response_code(400);
  exit;
}

/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
$accountid = (int) $results_xml->account_id;

fwrite($fh,"Added account for '%s' with $account_id '%d'{$nl}", $data['email'], $accountid);

fwrite($fh,"This email '%s' can be added to the $_SESSION and saved in database, etc.{$nl}", $data['email']);

} else {
  fwrite($fh,"Error: Invalid Product information, see error.txt\n");
  dump_response("Received invalid product information" );
}
/**
 * Post data to URL with cURL and return result XML string.
 *
 * Outputs cURL error and exits on failure.
 *
 * @param string $url
 * @param array  $data
 *
 * @return string
 */
function post_api_url($url, array $data = array()) {
	global $nl;

	// Initialize a new cURL resource and set the URL.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	// Form data must be transformed from an array into a query string.
	$data_query = http_build_query($data);

	// Set request type to POST and set the data to post.
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_query);

	// Set cURL to error on failed response codes from AllClients server,
	// such as 404 and 500.
	curl_setopt($ch, CURLOPT_FAILONERROR, true);

	// Set cURL option to return the response from the AllClients server,
	// otherwise $output below will just return true/false.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Post data to API.
	$output = curl_exec($ch);

	// Exit on cURL error.
	if ($output === false) {
		// It is important to close the cURL session after curl_error()
		fwrite($fh,"cURL returned an error: %s{$nl}", curl_error($ch));
		curl_close($ch);
    fclose($fh);
    http_response_code(400);
    exit;
	}

	// Close the cURL session
	curl_close($ch);

	// Return response
	return $output;
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
  $err = fopen($eFile, 'w');
  fwrite($err, $msg."\n");
  fwrite($err,"JSON DUMP\n");
  fwrite($err, pretty_dump($_REQUEST));
  fwrite($err,"\nEND OF DUMP\n");
  fwrite($err,"\n");
  fclose($err);
  return;
}
fclose($fh);
http_response_code(200);
//die();



?>
