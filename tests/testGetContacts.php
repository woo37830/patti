<?php
// Test the addContactNote


require '../webhook/get_contacts.php';

$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";

$contacts = getContacts($today, $from);
echo "\nContacts for $from as of $today\n";
$k = 1;
foreach($contacts as $contact){
  $active = $contact->inactive == "False" ? "active" : "inactive";
  echo "\n" . $k++ . "  $contact->firstname $contact->lastname - $contact->email - $active since: $contact->adddate\n";
}
?>
