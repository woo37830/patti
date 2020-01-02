<?php

require __DIR__ .  "/textmagic-rest-php/Services/TextmagicRestClient.php";

use Textmagic\Services\TextmagicRestClient;
use Textmagic\Services\RestException;

define('VERSION', '0.01');

//$request_body = file_get_contents('php://input');
//$json = json_decode($request_body);

// create logging function
function m_log($arMsg)
{
  $stEntry="";
  // Get event occur date time, when it happened.
  $arLogData['event_datetime']='['.date('D Y-m-d h:i:s A').'] [client '. $_SERVER['REMOTE_ADDR'].']';
  // if message is array type
  if(is_array($arMsg))
  {
    // concatenate msg with datetime
    foreach($arMsg as $msg)
      $stEntry.=$arLogData['event_datetime']." ".$msg."\r\n";
  }
  else
  { // concatenate msg with datetime
      $stEntry.=$arLogData['event_datetime']." ".$msg."\r\n";
  }
  // create file with current date name
  $stCurLogFileName='log_'.date('Ymd').'.txt';
  // open the file append mode.
  $fHandler=fopen(LOG_PATH.$stCurLogFileName,'a+');
  // write the info into the file
  fwrite($fHandler,$stEntry);
  // close handler
  fclose($fHandler);
}
file_put_contents('/tmp/php-vars.txt',var_export([$_POST,$_GET,$_REQUEST],true));

ini_set("log_errors",1);
ini_set("error_log","/tmp/php-error.log");
error_log( "\nRequest Parameters");
    error_log( "Time: " .  $_POST['messageTime']);
    error_log( "Phone: " . $_POST['sender']);
    error_log( "Msg: " . $_POST['text']);
?>
