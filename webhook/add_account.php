<?php

function add_account($api_endpoint, $account_id, $api_key, $account,
 $group_name, $thrivecartid, $email) {
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
$results_xml = thrivecart_api($url, $data, $fh); // returns simplexml_load_string object representation
if ($results_xml === false) {
	logit($results_xml, "\nError parsing XML", "failure");
  http_response_code(400);
	exit;
}
fwrite($fh,"results_xml = $results_xml->message\n");
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
  fwrite($fh,"An error occurred $fesults_xml->error\n");
  logit($data['email'],$results_xml->error, "failure" );
  http_response_code(400);
  exit;
}
/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
 $account_id = (int)$results_xml->accountid;

fwrite($fh,"account_id is '$account_id'\n");

// Here I write the account information using addUser in mysql_common.php
addUser($data['email'],   $thrivecartid, $account_id);
logit($account_id, json_encode($_REQUEST), "success - added account for email: ".$data['email']);
fclose($fh);
}
?>
