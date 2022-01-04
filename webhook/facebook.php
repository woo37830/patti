<?php

$verify_token = 'meatyhamhock';
$challenge = $_REQUEST['hub_challenge'];
if( $verify_token === $_REQUEST['hub_verify_token'] ) {
   echo $challenge;
}
?>
