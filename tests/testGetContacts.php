<?php
// Test the addContactNote


require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";

$result = getContacts($today, $from);
echo "\nResult of getContacts: $result\n";
?>
