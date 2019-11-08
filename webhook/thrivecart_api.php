<?php // thrivecart_api.php
// Expects a $data array containing data required for the particular AllClients api.
// Also, the $url for the call
// It will return a $results_xml structure if there is not an error.
//

require 'post_api_url.php';

function thrivecart_api($url, $data) {
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
	fwrite($fh,"\nAllClients API returned an error: ".$results_xml->error."\n");
  logit($data['email'],$results_xml->error, "failure" );
	fclose($fh);
  http_response_code(400);
  exit;
}

return $results_xml;
}
?>
