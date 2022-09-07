<?php
require '../get_all_accounts.php';
require '../Account.php';

$accounts = getAllAccounts();

echo "\nThere were a total of ".count($accounts)."\n";

$account = Account::read(2607);
if( $account->get_errMessage() != '' )
{
  echo "\n".$account->get_errMessage()."\n";
}
echo "All Done!";
