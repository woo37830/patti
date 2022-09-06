<?php // thrivecart_api.php
// Expects a $data array containing data required for the particular AllClients api.
// Also, the $url for the call
// It will return a $results_xml structure if there is not an error.
//

require 'post_api_url.php';

function thrivecart_api($url, $data) {
	$log_file = "./mysql-errors.log";

	// setting error logging to be active
	ini_set("log_errors", TRUE);

	// setting the logging file in php.ini
	ini_set('error_log', $log_file);

	libxml_use_internal_errors(true);
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
//echo "xml_string: $result_xml_string";
$results_xml = simplexml_load_string($result_xml_string);
if ( !isset($results_xml) ) {
    echo "Failed loading XML\n";
    foreach(libxml_get_errors() as $error) {
        error_log($error->message);
    }
		return false;
}

return $results_xml;
}
?>
