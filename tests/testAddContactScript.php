<?php
// Test the addContactNote


require '../webhook/add_contact.php';
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

$result = addContact($from, "Patrick", "Chamberlain", "p.chang@testers.com", "8885551212", "Check source", "103 W. North St.", "Tampa", "FL", "33303","Landing Page");
echo "Result for addContact: $result\n";
echo "All Done!\n";
?>
