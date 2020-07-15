<?php
// Test the addContactNote


require '../webhook/get_contact.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";
$to = "Ralph Gomez<wooten.666@gmail.com>";
$non_existent_email = "Joey Jones<jwooten37830@gmail.com>";

$result = getContact($today, $from, $to);
echo "\nResult of getContact(Which should exist): '$result'\n";
$result = getContact($today, $from, $non_existent_email);
echo "\nResult of getContact(Which does not exist): '$result'\n";
echo "All Done!\n";
?>
