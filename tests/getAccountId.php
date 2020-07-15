<?php
// Test the addContactNote
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';



$today = date("D M j G:i:s T Y");
$from = "jwooten37830@icloud.com";

if( account_exists($from) )
{
  $userId = getAccountId($from);
  echo "\nuserId = $userId";
}
else
{
  echo "\nNo account for $from";
}
echo "\nAll Done!\n";
?>
