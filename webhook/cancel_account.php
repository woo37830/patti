<?php

function cancel_account($api_endpoint) {
$url = $api_endpoint . 'SetAccountStatus.aspx';
// Get the account id from thrivecart, and then look up
// the accountid for AllClients and use it.
$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
	'accountid' => $thrivecartid,
	'status'  => 0,
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
cancelUser($data['email'],   $thrivecartid);
logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "success");

}
?>
