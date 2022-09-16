<?php

// Test the getContact


require '../webhook/get_contact.php';
require_once '../webhook/mysql_common.php';
require_once '../webhook/utilities.php';
require_once "../webhook/Contact.php";

//$result_xml_string = '<results><contacts><contact><id>807687</id><teammemberid>0</teammemberid><name>Wooten 666</name><email>wooten.666@gmail.com</email></contact></contacts></results>';
//$results = simplexml_load_string($result_xml_string);
//print_r($results);
//echo "contactid: ".$results->contacts->contact->id;
$cr = "\n<br/>";
$from = "jwooten37830@icloud.com";
$to = "p.chang@testers.com";

$non_existent_email = "Joey Jones<jwooten37830@gmail.com>";
$non_existent_email2 = "pdiddly@gmail.com";

$user = getUserByEmail($from);
//print_r($user);
echo "$cr Test getUser $from by Email: " . $user["engagemoreid"] . "$cr";

$result = getContactData($from, $to);
echo "$cr Test getContactData $from , $to: ".$result->contacts->contact->id."$cr";
//print_r($result);
echo "$cr";
$result = getContact($from, $to);
$id = $result->contacts->contact->id;
$teamid = $result->contacts->contact->teammemberid;
$name = $result->contacts->contact->name;
$mail = $result->contacts->contact->email;

echo "$cr Result of getContact $to in $from (Which should exist):'$id'<br /> $name $cr $mail $cr '$id'<br />  $cr";
$result = getContact(2607, $to);
$id = $result->contacts->contact->id;

echo "$cr Result of getContact $to in 2607(Which should exist): '$id' $cr";
$email = get_email_from_rfc_email($non_existent_email);
//echo $email;
$result = getContact(2607, $email);
echo "$cr Result of getContact $non_existent_email in 2607(Which does not exist): '$result'$cr";
$email = get_email_from_rfc_email($non_existent_email2);
//$result = getContact(2607, $email);
echo "$cr Result of getContact $non_existent_email2 in 2607(Which does not exist): '$result'$cr";
echo "$cr";

$contact = Contact::read(2607, $to);
echo "$cr Result of Contact.read(2607,$to) = $contact $cr";

 $contact = Contact::read(2607, $non_existent_email2);
echo "$cr Result of Contact.read(2607,$non_existent_email2) = $contact $cr";
       
echo "$cr All Done!$cr";
?>
