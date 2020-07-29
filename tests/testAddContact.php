<?php
// Test the addContactNote
session_start(); //don't forget to do this
$location = "/patti/tests/testAddContact.php";
if( !isset($_SESSION['auth']) ) {
  require('fancyAuthentication.php');
}



require '../webhook/add_contact.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "John Wooten<jwooten37830@test.com>";

//$restult = getContacts($today, $from);
//echo "\nResult of getContacts: $result\n";
$result = addContact($today, $from, $to);
echo "\nResult addContact: $result <br />\n";
?>
