<?php
// Test the addContactNote


require '../webhook/add_contact_note.php';
require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "Hector Gomez<wooten.666@gmail.com>";
$messageId = 123;
$subject = "Test AddContactNote to existing contact";
$message = "This is a test";
$attachmentLog = "A note about an attachment";
$postArray = "The complete data received in the request";


//$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
//echo "\nResult addContactNote: $result <br />\n";

$to = "Jeffrey Jones<jwooten37830@gmail.com>";
$messageId = 127;
$subject = "Test AddContactNote to non-existing contact jwooten37830@gmail.com";
$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
echo "\nResult addContactNote: $result <br />\n";

?>
