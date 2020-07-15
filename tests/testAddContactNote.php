<?php
// Test the addContactNote


require '../webhook/add_contact_note.php';
require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "Hector Gomez<xyz.666@gmail.com>";
$messageId = $today;
$subject = "Test AddContactNote to existing contact";
$message = "This is a test that contains <h1>Some Data in brackets</h1>";
$attachmentLog = "A note about an attachment";
$postArray = "The complete <a >data</a> received in the request";


//$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
//echo "\nResult addContactNote: $result <br />\n";

$subject = "Test AddContactNote to non-existing contact $to";
$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
echo "\nResult addContactNote: $result\n";

?>
