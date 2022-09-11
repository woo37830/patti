<?php

require 'Contact.php';

$contact = new Contact("   ACCOUNT=||jwooten37830@icloud.com|| (888) 555-1212 106 Crestview Lane Oak Ridge, TN 37830 Bobby AlmostNice bobby.an@testers.com ");
$contact->set_source("realEstate.com");
//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

$contactid = $contact->write();

if( isNullOrEmpty($contactid) || $contactid == -1 )
{
  echo "\nFailure to write: ".$contact->get_email()." to account: ".$contact->get_acct()."\n";
} else {
  echo "\nContact id: >$contactid< added";
}

$contact = Contact::read(2607, 'r.jones@gmail.com');
if( $contact->get_errMessage() != '' )
{
  echo "\n".$contact->get_errMessage()."\n";
}
echo "Contact ID: ".$contact->get_contactid();
echo "All Done!";

?>
