<?php
// Test the addContactNote


require '../webhook/add_contact_note.php';
require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
//$to = "Patti - AZ Sampson <patti@exposedagent.com>";
$to = "AllClients support+id30186@allclients.zendesk.com";
$messageId = $today;
$subject = "Test AddContactNote to a existing contact";
$message = "This is a test that contains <h1>Some Data in brackets</h1>";
$attachmentLog = "A note about an attachment";
$postArray = "The complete <a >data</a> received in the request";


//$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
//echo "\nResult addContactNote: $result <br />\n";

try
{
$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
echo "\n<br />Result addContactNote: $result<br/>\n";


$to = "Patti AZ Sampson<patti@exposedagent.com>";
$messageId = 666;
$subject = "Test AddContactNote to an agent in engagemorecrm";
$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
echo "\n<br />Result addContactNote: $result<br />\n";
} catch( Exception $e )
{
  echo "\n<br />An Exception occurred in testAddContactNote.php: $e\n<br />";
}
?>
