<?php
// Test the addContactNote
session_start(); //don't forget to do this
$location = "/patti/tests/testAddContactNote.php";
if( !isset($_SESSION['auth']) ) {
  require('fancyAuthentication.php');
}
function getHTML() {
$html = <<<EOS
<html>
    <head>
          <style type="text/css" media="screen">
html {
/*      background-color: #c8dcff; */
 }

body
{
    color:#404040;
/*   background-color: powderblue; */
    font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
}

#page
{
  position: relative;
  min-height: 100%;
}

#content
{
  padding-bottom: 1.5rem; /* Footer height */
}

footer
{
  width: 100%;
  position: absolute;
  bottom: 0;
  padding-top: 3px;
  background-color: powderblue;
  text-align: center;
  height: 1.5rem;
  font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
  font-style: italic;
}
</style>
    </head>
<body>
<center>
  <br />
  <h1>Add Contact Note</h1>
EOS;
return $html;
}

function getHTML2() {
$html2 = <<<EOS2
  <hr />
  <br />
<form action="" method="post">
From: <input type="text" name="from" value="jwooten37830@icloud.com"><br />
To: <input type="text" name="to" value="correspondent@gmail.com"><br />
Message: <input type="text" name="message" value="A message"><br />
Subject: <input type="text" name="subject"><br />
Source: <input type="text" name="source"><br />
<input type="submit" name="submit" value="Submit">
</form>
<br /><br /><a href='index.php' />Home</a>
</center>
EOS2;
  return $html2;
}

require '../webhook/add_contact_note.php';
require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
echo "<center>$today<br /><hr />";
if(isset($_POST['submit'])){

$from = trim($_POST['from']);
//$to = "Patti - AZ Sampson <patti@exposedagent.com>";
$to = trim($_POST['to']);
$messageId = $today;
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);
$attachmentLog = "A note about an attachment";
$postArray = "The complete <a >data</a> received in the request";


//$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
//echo "\nResult addContactNote: $result <br />\n";

try
{
$result = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
echo "\n<br />Result addContactNote: $result<br/>\n";

}
catch( Exception $e1) {
  echo "<br />Exception $e1<br />";
  generateCallTrace();
  die("\nException $e1\n");
}
}
echo getHTML();
echo getHTML2();
include '../webhook/git-info.php';
echo "</footer></div></body></html>";
?>
