<?php

require __DIR__ .  "/textmagic-rest-php/Services/TextmagicRestClient.php";

use Textmagic\Services\TextmagicRestClient;
use Textmagic\Services\RestException;

define('VERSION', '0.01');

/**
  *  * Client object
  *   */
$client = new TextmagicRestClient('pattisampson', 'AIMRx8N1BKnpfiIAJEVUSdmqUbTt08');
$result = ' ';
# Ensure that to has the 1 in front and no ( or -

if( $_REQUEST['phone'] ) {
$phone = $_REQUEST['phone'];
} else {
  $phone = $_REQUEST['phone1'];
}

  
$to = preg_replace('/\D+/', '',  $phone );
if( strlen($to) == 0 ) {
  echo "Neither 'phone' nor 'phone1' had a value!";
  exit;
}

if( strlen($to) == 10 ) {
  $to = "1" . $to;
}

$msg = $_REQUEST['msg'];
if( !$msg ) {
  $msg = "A msg field was not provided!";
}

$msg = "Hi " . $_REQUEST['firstname'] . ",\r\n" . $msg  . ". From EngageMoreCRM";
try {
$result = $client->messages->create(
array(
     'text' => $msg,
     'phones' => $to
      )
    );
}
catch (\Exception $e) {
   if ($e instanceof RestException) {
   print "Exception: " . '[ERROR] ' . $e->getMessage() . ", phones = " . $to . "\n";
   foreach ($e->getErrors() as $key => $value) {
      print "X " . '[' . $key . '] ' . implode(',', $value) . "\n";
   }
} else {
    print "Error: " . '[ERROR] ' . $e->getMessage() . ", to = " . $to . "\n";
   }
   return;
}
echo "Sent message '" . $msg . "' to " . $to . " with id: " . $result['id'] . "\n";
?>
