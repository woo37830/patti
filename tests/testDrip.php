<?php
// Test the addContactNote
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';

$email = 'ralph.test1@testers.com';

$today = date("D M j G:i:s T Y");
// where are we posting to?
$url = 'https://secure.engagemorecrm.com/api/t/wf/r9zo6543z2/18a1ca9de57ecbc02279';

    // what post fields?
    $fields = array(
       'email' => $email,
    );

    // build the urlencoded data
    $postvars = http_build_query($fields);

    // open connection
    $ch = curl_init();

    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

    // execute post
    $result = curl_exec($ch);

    // close connection
    curl_close($ch)
?>
