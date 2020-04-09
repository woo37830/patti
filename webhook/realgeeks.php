<?php
//
require 'config.ini.php';
require 'utilities.php';
/**
 * RealGeeks Account ID and API Key.
 */
$account_id   = $config['REALGEEKS_USER'];
$api_key      = $config['REALGEEKS_PASSWORD'];

$date = (new DateTime('NOW'))->format("y:m:d h:i:s");

$json_data = json_encode($_REQUEST);

echo $json_data;

?>
