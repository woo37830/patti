<?php
require('DumpHTTPRequestToFile.php');
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['thrivecart_secret' ]) || $_REQUEST['thrivecart_secret'] != "amazinglysecurekey" ){
 dump_response("Key Failure");
 http_response_code(200);
 die();
}

// Look for the order.success webhook event. Make sure the response is complete before processing.
if( $_REQUEST['event'] == "order.success" && ! empty( $_REQUEST['customer'] ) ){
  dump_response();
} else {
  dump_response("Invalid event");
}
//$content = json_encode($_REQUEST);
//file_put_contents($file, $content);
//(new DumpHTTPRequestToFile)->execute('./dumprequest.txt');
//var_dump_pre($_REQUEST);
//echo pretty_dump($_REQUEST);

function pretty_dump($mixed = null) {
  ob_start();
  echo json_encode($_REQUEST, JSON_PRETTY_PRINT);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function var_dump_pre($mixed = null) {
  echo '<pre>';
  var_dump($mixed);
  echo '</pre>';
  return null;
}

function var_dump_ret($mixed = null) {
  ob_start();
  var_dump($mixed);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
//file_put_contents($file, var_dump_ret($_REQUEST));
function dump_response($msg = null) {
  $myFile = "response.txt";
  $fh = fopen($myFile, 'w');
  fwrite($fh, $msg."\n");
  fwrite($fh, pretty_dump($_REQUEST));
  fclose($fh);
}

http_response_code(200);
//die();



?>
