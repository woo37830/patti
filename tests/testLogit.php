<?php
// Test the addContactNote
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';



$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "jwooten37830@gmail.com";
$messageId = 123;
$subject = "Test AddContactNote";
$message = "This is a test";
$attachmentLog = "A note about an attachment";
$postArray = "The complete data received in the request";

logit($from, $postArray, $message);
echo "\nAll Done!\n";
?>
