<?php
//  Function to make a curl post to provided url using array of fields
// returns the result array
function curlPost($url, $fields) {

require 'config.ini.php';
require 'mysql_common.php';

// SET ERROR REPORTING SO WE CAN DEBUG OUR SCRIPTS EASILY
//error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

$today = date("D M j G:i:s T Y");
   try {
     logit("TEST"",$url, "Begin curlPostr" );
    // build the urlencoded data
    $postvars = http_build_query($fields);

    // open connection
    $ch = curl_init();

    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

    // execute post
    $result = curl_exec($ch);
    $response_data = json_decode($result);
    // close connection
    curl_close($ch);
  }
  catch( Exception $e) {
    logit("EXCEPTION"",$e->getMessage(), "Exception occurred." );
  }
    return $response_data;

}
?>
