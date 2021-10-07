<?php
//  Function to make a curl post to provided url using array of fields
// returns the result array
function curlPost($url, $fields) {

require 'config.ini.php';

date_default_timezone_set('America/New_York');

$today = date("D M j G:i:s T Y");

     logit("TEST",$url, "Begin curlPost" );
    // build the urlencoded data
    $postvars = http_build_query($fields);
    logit("TEST","postvars", "after postvars" );

    // open connection
    $ch = curl_init();
    logit("TEST","ch", "after curl_init" );

    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    logit("TEST","setopt", "after curl_setopts" );

    // execute post
    $result = curl_exec($ch);
    logit("TEST","result", "after curl_exect, $result" );
    $response_data = json_decode($result);
    logit("TEST","response_data", "after decode" );

    // close connection
    curl_close($ch);

    return $response_data;

}
?>
