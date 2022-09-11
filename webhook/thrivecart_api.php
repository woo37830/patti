<?php // thrivecart_api.php
// Expects a $data array containing data required for the particular AllClients api.
// Also, the $url for the call
// It will return a $results_xml structure if there is not an error.
//

require 'post_api_url.php';

/**
 * Remove substring from string
 *
 * @param string $subject
 * @param mixed $remove[optional] If omitted the function will remove whitespaces
 * @return mixed
 */
function rmv($subject, $remove = " ") {
    $return = $subject;

    if (!is_array($remove)) {
        $remove = array($remove);
    }

    for ($i = 0; $i < count($remove); $i++) {
        $return = str_replace($remove[$i], "", $return);
    }

    return $return;
}

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

$result_xml_string = rmv($result_xml_string,'<?xml version="1.0"?>',$result_xml_string);
//  $results = simplexml_load_string($result_xml_string);
//  print_r($results);
//  echo "\ncontactid: ".$results->contacts->contact->id."\n";
//echo $result_xml_string;
/**
 * SimpleXML will create an object representation of the XML API response. If
 * the XML is invalid, simplexml_load_string will return false.
 *
 * @var SimpleXMLElement $results_xml
 */
//echo "xml_string: $result_xml_string";
$results_xml = simplexml_load_string($result_xml_string);
//print_r($results_xml);
if ( isset($results_xml->error) ) {
//    $results_xml = $result_xml_string;
	//$results_xml = "\nresults_xml is not set or false, result_xml_string: \n".$result_xml_string;
    foreach(libxml_get_errors() as $error) {
        error_log($error->message);
    }
		return false;
}

return $results_xml;
}
?>
