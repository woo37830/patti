<?php
// Test the addContactNote


require '../webhook/get_contact.php';
require_once '../webhook/mysql_common.php';
require_once '../webhook/utilities.php';

//$result_xml_string = '<results><contacts><contact><id>807687</id><teammemberid>0</teammemberid><name>Wooten 666</name><email>wooten.666@gmail.com</email></contact></contacts></results>';
//$results = simplexml_load_string($result_xml_string);
//print_r($results);
//echo "contactid: ".$results->contacts->contact->id;

$from = "jwooten37830@icloud.com";
$to = "wooten.666@gmail.com";

$non_existent_email = "Joey Jones<jwooten37830@gmail.com>";
$non_existent_email2 = "pdiddly@gmail.com";

$user = getUserByEmail($from);
//print_r($user);
echo "getUser $from by Email: ".$user["engagemoreid"]."\n";

$result = getContact($from, $to);
echo "\nResult of getContact $to in $from (Which should exist): '$result'\n";
$result = getContact(2607, $to);
echo "\nResult of getContact $to in 2607(Which should exist): '$result'\n";
$email = get_email_from_rfc_email($non_existent_email);
//echo $email;
$result = getContact(2607, $email);
echo "\nResult of getContact $non_existent_email in 2607(Which does not exist): '$result'\n";
$email = get_email_from_rfc_email($non_existent_email2);
//$result = getContact(2607, $email);
echo "\nResult of getContact $non_existent_email2 in 2607(Which does not exist): '$result'\n";
echo "All Done!\n";
?>
