<?php

function add_account($api_endpoint, $account_id, $api_key, $account,
 $group_name, $thrivecartid) {
/**
 * Specify URL and form fields for AddContact API function.
 */
$url = $api_endpoint . 'AddAccount.aspx';
$myFile = "add_account.txt";
$fh = fopen($myFile, 'a');

$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
	'email' => $account['email'],
	'password'  => $account['password'],
        'group' => $group_name,
);
fwrite($fh,"data array established\n");
$results_xml = thrivecart_api($url, $data, $fh);
fwrite($fh,"\nresults_xml = " . simplexml_load_string($results_xml) . "\n");
if ($results_xml === false) {
	logit(simplexml_load_string($results_xml), "\nError parsing XML", "failure");
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
  logit($data['email'],$results_xml->error, "failure" );
  http_response_code(400);
  exit;
}
/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
$accountid = (int) $results_xml->account_id;


// Here I write the account information using addUser in mysql_common.php
addUser($data['email'],   $thrivecartid, $account_id);
logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "success");
fclose($fh);
}
?>
