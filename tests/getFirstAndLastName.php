<?php
// Test the addContactNote
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';



$today = date("D M j G:i:s T Y");
$email = array();
$email[0] = "John Wooten<jwooten37830@icloud.com>";
$email[1] = "Patti - AZ Sampson <patti@exposedagent.com>";
$email[2] = "AllClients support+id30186@allclients.zendesk.com";
$email[3] = "John <john@email.com>";
for( $i = 0; $i < sizeof($email); $i++ )
{
$names = firstAndLastFromEmail($email[$i]);
  echo "\n$i) email = '$email[$i]'";
  echo "\n\tfrom - First: $names[0], Last: $names[1], email: $names[2]\n";
}
echo "\nAll Done!\n";
?>
