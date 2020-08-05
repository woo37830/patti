<?php
// Test the addContactNote


require '../webhook/get_accounts.php';

$today = date("D M j G:i:s T Y");

$accounts = getAccounts();
$k = 1;
foreach($accounts as $account){
  $active = $account->inactive == "False" ? "active" : "inactive";
  echo "\n" . $k++ . ")  $account->firstname $account->lastname - $account->email - $active since: $account->adddate\n";
}
?>
