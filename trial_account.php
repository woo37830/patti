<?php

#require __DIR__ .  "/textmagic-rest-php/Services/TextmagicRestClient.php";

#use Textmagic\Services\TextmagicRestClient;
#use Textmagic\Services\RestException;

define('VERSION', '0.01');

//$request_body = file_get_contents('php://input');
//$json = json_decode($request_body);

file_put_contents('/tmp/php-vars.txt',var_export([$_POST,$_GET,$_REQUEST],true));

ini_set("log_errors",1);
ini_set("error_log","/tmp/php-error.log");
error_log( "\nRequest Parameters");
#    error_log( "Name: " .  $_REQUEST['firstname']);
//    error_log( "Password: " . $_REQUEST['password']);
#    error_log( "Email: " . $_REQUEST['email']);
#    error_log( "Phone: " . $_REQUEST['phone']);
$firstname = $_GET['firstname'];
$email = $_GET['email'];
  echo "<center><h1>Thank You, $firstname!</h1><br /><h2>     We have received your information.</h2><br /><h3>   You should receive an email soon at $email!</h3></center>";
?>
