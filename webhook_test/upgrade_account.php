<?php

function upgrade_account($api_endpoint, $account_id, $api_key, $account,
 $group_name, $productid, $email) {
/**
 * Specify URL and form fields for AddContact API function.
 */
$url = $api_endpoint . 'SetAccountGroup.aspx';
$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
  'accountid'  => $account, // the engagemore id
  'group' => $group_name,
);
$results_xml = thrivecart_api($url, $data); // returns simplexml_load_string object representation
if ($results_xml === false) {
	logit($email, "", "failure parsing xml: ".$results_xml);
  http_response_code(400);
	exit;
}
if (isset($results_xml->message)) {
  logit($email,"","Upgrade result: $results_xml->message");
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
  logit($email,$results_xml->error, "failure" );
  http_response_code(400);
  exit;
}

  // Here I write the account information using updateProduct in mysql_common.php
  updateProduct($account, $productid);
  logit($email, "", "success upgraded account");
  return $account;
}
?>
