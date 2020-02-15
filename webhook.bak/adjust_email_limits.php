<?php

function adjust_email_limits($api_endpoint, $account_id, $api_key, $account,
   $email,  $product, $limits) {
logit($email, "Adjusting email limits to align with $product","adjust_email_limits: $account");
/**
 * Calculate the adjustment depending upon the product id, assuming thrivecart
 * the default is 15000 for now.  Otherwise have to query then Calculate
 */
 if( !array_key_exists($product, $limits )) {
  logit($email,json_encode($limits),"FAILURE: $product does not exist in limits table");
  return -1;
}
$limit = (int)$limits[$product]; // Will be 5000 or 10000;
// assuming 15000 limit, then if limit is 5000, subtract 10000, else subtract 5000;
logit($email, json_encode($limits),"limit for $product is $limit");
if( $limit == 5000 )
{
  $credit = (int)-10000;
} else {
  $credit = (int)-5000;
}
logit($email, json_encode($limits),"credit is $credit");

/**
 * Specify URL and form fields for AddContact API function.

 */

$url = $api_endpoint . 'AddEmailCredits.aspx';

$data = array(
	'apiusername' => $account_id,
	'apipassword'    => $api_key,
	'accountid' => $account,
  'addemailcredits' => $credit,
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
  logit($email,"", "FAILURE: $results_xml->error" );
  return -1;
}
/**
 * If no error was returned, the AddEmailCredits results object will contain a
 * 'priorpaidcreditsbalance, and a newpaidcreditsbalance, which can be cast as integers.
 */
 $prior_balance = (int)$results_xml->priorpaidcreditsbalance;
 $new_balance = (int)$results_xml->newpaidcreditsbalance;

  logit($email, "", "SUCCESS: Adjusted email credits by $credit.  Had: $prior_balance, now has: $new_balance");
  return 0;
}
?>
