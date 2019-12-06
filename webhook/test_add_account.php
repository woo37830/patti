<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];

$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
$products = array( "product-9" => "RE - BUZZ ($69)", "product-X')" => "GROUP-X");

$myFile = "response.txt";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
if( $fh = fopen($myFile, 'a') ) {
fwrite($fh, "\n-----------------".$date."-----------------------------------\n");
}

/**
 * The API endpoint and time zone.
 */
$api_timezone = new DateTimeZone('America/New_York');


$event = 'order.subscription_cancelled';
$thrivecartid = 13118877;
//$thrivecartid = 225; // Bad id

  $product = 'product-9';
  fwrite($fh,"\nThe item_identifier is '".$product."'\n");

if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
  fwrite($fh,"\nProcessing item_identifier: '".$product."'\n");
  $group_name = $products[$product];
  fwrite($fh, "\nThe group will be: ".$group_name."\n");
/**
 * The contact information to insert.
 *
 * Information will be added to your AllClients contacts!
 */
$account = array(
	'email' => 'jwooten37830@me.com',
	'password'  => 'engage123',
);

$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

if( $event == "order.success") {
  fwrite($fh, "\nProcessing order.success\n");
  add_account($api_endpoint,$account_id, $api_key, $account, $group_name, '123');
} else if( $event == "order.subscription_cancelled") {
  fwrite($fh, "\nProcessing subscription_cancelled\n");
  $status = change_account_status($fh, $api_endpoint, $account_id, $api_key, $thrivecartid,0);
  fwrite($fh, $status . "\n");

}

} else {
  logit('email',"Error: Invalid Product information, got item_id='".$product."'", "failure");
  logit('email', 'request', "failure - Invalid product");
}
// Log this failure using logit


fwrite($fh,"\n-----------------------------------------\n");
fclose($fh);
http_response_code(200);
//die();



?>
