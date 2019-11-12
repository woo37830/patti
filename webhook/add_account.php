<?php

function add_account($api_endpoint) {
/**
 * Specify URL and form fields for AddContact API function.
 */
$url = $api_endpoint . 'AddAccount.aspx';
$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
	'email' => $account['email'],
	'password'  => $account['password'],
  'group' => $group_name, // Here we set the group name depending upon the product
);

$results_xml = thrivecart_api($url, $data);
if ($results_xml === false) {
	logit($results_xml, "\nError parsing XML", "failure");
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

}
?>
