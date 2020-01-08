<?php
$myFile = "response.txt";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
if( $fh = fopen($myFile, 'a') ) {
fwrite($fh, "\n-----------------".$date."-----------------------------------\n");
}
http_response_code(200);
fwrite($fh,"request:".json_encode($_REQUEST)."\n");
fclose($fh);
die();
?>
