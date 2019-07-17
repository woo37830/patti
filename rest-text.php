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
try {
$result = $client->messages->create(
array(
     'text' => 'Hello from TextMagic PHP',
     'phones' => implode(', ', array('99900000'))
      )
   );
}
catch (\Exception $e) {
   if ($e instanceof RestException) {
   print '[ERROR] ' . $e->getMessage() . "\n";
   foreach ($e->getErrors() as $key => $value) {
      print '[' . $key . '] ' . implode(',', $value) . "\n";
   }
} else {
    print '[ERROR] ' . $e->getMessage() . "\n";
   }
   return;
}
echo $result['id'];
?>
