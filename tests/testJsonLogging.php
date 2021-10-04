<?php
// Test the addContactNote
//require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';



$today = date("D M j G:i:s T Y");
$from = "John Wooten<jwooten37830@icloud.com>";
$to = "jwooten37830@gmail.com";
$messageId = 123;
$subject = "Test AddContactNote";
$message = "This is a test of json.stringify ";
$attachmentLog = "A note about an attachment";
$jsonData = '{
  "results": {
    "message": "Success",
    "contactid": "697611",
    "isduplicate": "True",
    "duplicaterule-updatecontact": "True",
    "duplicaterule-runtriggger": "True"
  }';
$postArray = "The <br />complete data<br /> received \/ in the <xml><value>request</value></xml>";


echo "\n Using $from and a postArray with xml tags '$jsonData'\n";
logit($from, $jsonData, $message);
echo "\nAll Done!\n";
?>
