<?php
// Test the postCurl function usiing drip for email contact
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/curlPost.php';

$email = 'ralph.test1@testers.com';

$today = date("D M j G:i:s T Y");
// where are we posting to?
$url = 'https://secure.engagemorecrm.com/api/t/wf/r9zo6543z2/18a1ca9de57ecbc02279';

    // what post fields?
    $fields = array(
       'email' => $email,
    );


    // execute post
    $result = curlPost($url, $fields);

    // close connection
?>
