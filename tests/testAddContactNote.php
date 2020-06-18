<?php
// Test the addContactNote


require '../webhook/add_contact_note.php';
require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "Hector Gomez<wooten.666@gmail.com>";
$messageId = 123;
$subject = "Test AddContactNote";
$message = "This is a test";
$attachmentLog = "A note about an attachment";
$postArray = "The complete data received in the request";

//$restult = getContacts($today, $from);
//echo "\nResult of getContacts: $result\n";
$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
echo "\nResult addContactNote: $result <br />\n";
?>
