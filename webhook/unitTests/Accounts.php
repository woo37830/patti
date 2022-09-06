<?php
require '../get_all_accounts.php';

$accounts = getAllAccounts();

echo "\nThere were a total of ".count($accounts)."\n";
echo "All Done!";
