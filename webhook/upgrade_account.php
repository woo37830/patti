<?php

function upgrade_account($api_endpoint, $account_id, $api_key, $account,
 $group_name, $productid, $email) {
/**
 * Specify URL and form fields for AddContact API function.
 */
$url = $api_endpoint . 'SetAccountGroup.aspx';
$myFile = "upgrade_account.txt";
$fh = fopen($myFile, 'a');
echo "\nupgrade_account.php -> accountid = $account, email = $email, new group = $group_name with productid = $productid\n";
$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
  'accountid'  => $account, // the engagemore id
  'group' => $group_name,
);
$results_xml = thrivecart_api($url, $data, $fh); // returns simplexml_load_string object representation
if ($results_xml === false) {
  fwrite($fh,"results_xml = $results_xml->message\n");
  echo "\nError parsing xml $results_xml->message";
	logit($results_xml, "\nError parsing XML", "failure");
  http_response_code(400);
	exit;
}
if (isset($results_xml->message)) {
  echo "\nresults of upgrade = $results_xml->message";
  fwrite($fh,"results_xml = $results_xml->message\n");
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
  echo "\nAn error occurred during upgrade: $results_xml->error";
  fwrite($fh,"An error occurred: $results_xml->error\n");
  logit($account,$results_xml->error, "failure" );
  http_response_code(400);
  exit;
}

// Here I write the account information using addUser in mysql_common.php
updateProduct($account, $productid);
logit($email, json_encode($_REQUEST), "success");
fclose($fh);
}
?>
