<?php
// Test the addContactNote


require '../webhook/add_contact_note.php';
require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "John Wooten<jwooten37830@me.com>";
$messageId = $today;
$subject = "Test AddContactNote to a non-existing contact";
$message = "This is a test that contains <h1>Some Data in brackets</h1>";
$attachmentLog = "A note about an attachment";
$postArray = "The complete <a >data</a> received in the request";


//$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
//echo "\nResult addContactNote: $result <br />\n";

try
{
$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
$resultStr = $result ? "Succeeded" : "Failed";
echo "\nResult addContactNote: $resultStr\n";
}
catch( exception $e )
{
  echo "\Result addContactNote: Exception $e";
}

?>
