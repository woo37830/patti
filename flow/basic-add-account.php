<?php
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

/**
 * The contact information to insert.
 *
 * Information will be added to your AllClients contacts!
 */
$account = array(
	'email' => 'xxx@yyy',
	'password'  => '123123',
);

/**
 * Newline character, to support browser or CLI output.
 */
$nl = php_sapi_name() === 'cli' ? "\n" : "<br>";

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
		printf("cURL returned an error: %s{$nl}", curl_error($ch));
		curl_close($ch);
		exit;
	}

	// Close the cURL session
	curl_close($ch);

	// Return response
	return $output;
}

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
	print("Email and password must be specified at top of file to run.{$nl}");
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
	print("Error parsing XML{$nl}");
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
	printf("AllClients API returned an error: %s{$nl}", $results_xml->error);
	exit;
}

/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
$accountid = (int) $results_xml->account_id;

printf("Added account with $account_id '%d'{$nl}", $accountid);

printf("This can be added to the $_SESSION and save in database, etc.{$nl}");
