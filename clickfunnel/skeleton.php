<?php

$verify_token = 'meatyhamhock';
$challenge = $_REQUEST['hub_challenge'];
if( $verify_token === $_REQUEST['hub_verify_token'] ) {
   echo $challenge;
}
$input = json_decode(file_get_contents('php://input'), true);
error_log(print_r($input, true))
?>
