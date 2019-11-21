<?php

function cancel_account($api_endpoint, $account_id, $api_key, $thrivecartid) {
$url = $api_endpoint . 'SetAccountStatus.aspx';
// Get the account id from thrivecart, and then look up
// the accountid for AllClients and use it.


/**
 * If no error was returned, the AddContact results object will contain a
 * 'contactid' child SimpleXMLElement, which can be cast to an integer.
 */
$accountid = (int) getAccountId($thrivecartid);
$url = $api_endpoint . 'SetAccountStatus.aspx';
$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
	'accountid' => $accountid,
	'status'  => 0,
);
$result_xml_string = post_api_url($url, $data);
$results_xml = simplexml_load_string($result_xml_string);

if (isset($results_xml->error)) {
  fwrite($fh,"An error occurred $fesults_xml->error\n");
  logit($data['email'],$results_xml->error, "failure" );
  return 'Failed';
}
// Here I write the account information using addUser in mysql_common.php
$status = updateAccountStatus($accountid, 'inactive');
logit($accountid, json_encode($_REQUEST), "success - set account inactive");

}
?>
