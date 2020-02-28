<?php

function add_account($api_endpoint, $account_id, $api_key, $account,
 $group_name, $email, $product, $invoiceid, $orderid, $json_data) {
/**
 * Specify URL and form fields for AddContact API function.
 */

$url = $api_endpoint . 'AddAccount.aspx';

$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
	'email' => $email,
	'password'  => $account['password'],
  'group' => $group_name,
  'invoiceid' => $invoiceid,
  'orderid' => $orderid,
);
$results_xml = thrivecart_api($url, $data); // returns simplexml_load_string object representation
if ($results_xml === false) {
	logit($email, "", "FAILURE: Error parsing XML");
	return -1;
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
  logit($email,$json_data, "FAILURE: $results_xml->error" );
  echo "Failure: " . $results_xml->error . "<br />";
  return -1;
}
/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
 $account_id = (int)$results_xml->accountid;

  // Here I write the account information using addUser in mysql_common.php
  addUser($email,   $account_id, $product, $invoiceid, $orderid);
  return $account_id;
}
?>
