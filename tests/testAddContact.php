<?php
// Test the addContactNote


require '../webhook/add_contact.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "John Wooten<jwooten37830@mac.com>";
$messageId = 123;
$subject = "Test AddContactNote";
$message = "This is a test";
$attachmentLog = "A note about an attachment";
$postArray = "The complete data received in the request";

//$restult = getContacts($today, $from);
//echo "\nResult of getContacts: $result\n";
$result = addContact($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
echo "\nResult addContact: $result <br />\n";
?>
