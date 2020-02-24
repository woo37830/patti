<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'DumpHTTPRequestToFile.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];
$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';


echo "<html><head></head><body><h1>OK</h1></body></html>";
$json_data = json_encode($_REQUEST);

/**
 * The API endpoint and time zone.
 */
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
/*if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != $config['THRIVECART_SECRET'] ){
logit("INVALID", $json_data, "No key supplied");
 die('Invalid request, no key supplied');
}
*/

// Message seems to be from RealGeek so log it.
// Look for the order.success webhook event. Make sure the response is complete before processing.
logit("RealGeek", $json_data, "Data Received");
$dumpr = new DumpHTTPRequestToFile();
$dumpr -> execute("realgeek.txt");


?>
