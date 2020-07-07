<?php // thrivecart_api.php
// Expects a $data array containing data required for the particular AllClients api.
// Also, the $url for the call
// It will return a $results_xml structure if there is not an error.
//

require 'post_api_url.php';

function thrivecart_api($url, $data) {


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
	logit("Not found", json_encode($data), "FAILURE: thrivecart_api: Error parsing XML");
	exit;
}

return $results_xml;
}
?>
