<?php
// Test the addContactNote
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';



$today = date("D M j G:i:s T Y");
$from = "John Wooten<jwooten37830@icloud.com>";
$to = "John Wooten<jwooten37830@mac.com>";
$names = firstAndLastFromEmail($from);
echo "\nFirst: $names[0], Last: $names[1], email: $names[2]\n";
$names = firstAndLastFromEmail($to);
echo "\nFirst: $names[0], Last: $names[1], email: $names[2]\n";

echo "\nAll Done!\n";
?>
