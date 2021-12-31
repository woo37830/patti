<?php
// Test the addContactNote


require '../webhook/get_all_accounts.php';

$today = date("D M j G:i:s T Y");

$accounts = getAllAccounts();
$k = 1;
foreach($accounts as $account){
  $active = $account->status == "Active" ? "active" : "inactive";
  echo "\n" . $k++ . " $account->accountid: $account->email - $account->mailmerge_fullname, $active since: $account->create_date\n";
}
?>
