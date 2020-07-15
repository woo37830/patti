<?php
// Test the addContactNote
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';



$today = date("D M j G:i:s T Y");
$from = "John Wooten<jwooten37830@icloud.com>";
$to = "Patti - AZ Sampson <patti@exposedagent.com>";
$names = firstAndLastFromEmail($from);
echo "\nfrom - First: $names[0], Last: $names[1], email: $names[2]\n";
//echo "\nto = $to\n";
$displayName = get_displayname_from_rfc_email($to);
//echo "\ndisplayName = '$displayName'\n";
$email = get_email_from_rfc_email( $to );
//echo "\nemail = $email\n";
$names2 = firstAndLastFromEmail($to);
if ( sizeof( $names2) < 3 )
{
  echo "\nto - Name: $names2[0], email: $names2[1]\n";

} else {
    echo "\nto - First: $names2[0], Last: $names2[1], email: $names2[2]\n";
  }

echo "\nAll Done!\n";
?>
