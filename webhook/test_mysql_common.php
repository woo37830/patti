<?php
// test the common mysql code

require 'mysql_common.php';

// SET ERROR REPORTING TO DEBUG EASILY
error_reporting(E_ALL);

$user = 'jwooten37830@me.com';
$json = 'This is some json';
$status = 'success';

logit($user, $json, $status);
echo 'It worked!';
?>
