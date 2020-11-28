<?php
//require 'config.ini.php';
/*
$conn = new mysqli($config['PATTI_DATABASE_SERVER'],$config['DATABASE_USER'],$config['DATABASE_PASSWORD'],$config['PATTI_DATABASE']);
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
*/
// SET ERROR REPORTING SO WE CAN DEBUG OUR SCRIPTS EASILY
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

function connect($db) {
  require 'config.ini.php';

$db_host = $config['PATTI_DATABASE_SERVER']; // PROBABLY THIS IS OK
$db_name = $db;        // GET THESE FROM YOUR HOSTING COMPANY
$db_user = $config['PATTI_DATABASE_USER'];
$db_word = $config['PATTI_DATABASE_PASSWORD'];

// OPEN A CONNECTION TO THE DATA BASE SERVER AND SELECT THE DB
$mysqli = new mysqli($db_host, $db_user, $db_word, $db_name);

// DID THE CONNECT/SELECT WORK OR FAIL?
if ($mysqli->connect_errno)
{
//    die("mysql connect error: $mysqli->connect_error");
    $err
    = "CONNECT FAIL: "
    . $mysqli->connect_errno
    . ' '
    . $mysqli->connect_error
    ;
    trigger_error($err, E_USER_ERROR);
}
return $mysqli;
}

?>
