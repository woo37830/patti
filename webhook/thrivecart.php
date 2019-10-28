<?php
require('DumpHTTPRequestToFile.php');

/**
 * AllClients Account ID and API Key.
 */
$account_id   = '4K9vV0InIxP5znCa7d';
$api_key      = 'ie6n85dF826iYe5npA';

/**
 * The API endpoint and time zone.
 */
$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';
$api_timezone = new DateTimeZone('America/New_York');

// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != "IEYDASLZ8FR7" ){
 dump_response("Key Failure");
 http_response_code(403);
 die();
}

$myFile = "response.txt";
$fh = fopen($myFile, 'w');
// Look for the order.success webhook event. Make sure the response is complete before processing.
if( $_REQUEST['event'] == "order.success" && ! empty( $_REQUEST['customer'] ) ){
  dump_response();
  //process_response();
} else {
  fwrite($fh,"Invalid event.{$nl}");
  fclose($fh);
  http_response_code(418);
  die();
  //dump_response("Invalid event");
}
/**
 * The contact information to insert.
 *
 * Information will be added to your AllClients contacts!
 */
$account = array(
	'email' => $_REQUEST['customer']['email'],
	'password'  => '123123',
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

function var_dump_pre($mixed = null) {
  echo '<pre>';
  var_dump($mixed);
  echo '</pre>';
  return null;
}

function var_dump_ret($mixed = null) {
  ob_start();
  var_dump($mixed);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
//file_put_contents($file, var_dump_ret($_REQUEST));
function dump_response($msg = null) {
  $myFile = "response.txt";
  $fh = fopen($myFile, 'w');
  fwrite($fh, $msg."\n");
  fwrite($fh, pretty_dump($_REQUEST));
  fwrite($fh,"\n");
  //$json=json_encode($_REQUEST);
  //$obj=json_decode($json);
  //fwrite($fh,"Email: ".$obj->{"customer"}->{"email"});
  //fwrite($fh,"\n");
  fwrite($fh,"Email: ".$_REQUEST['customer']['email']);
  fclose($fh);
}
fclose($fh);
http_response_code(200);
//die();



?>
