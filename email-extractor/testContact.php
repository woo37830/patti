<?php
require '../webhook/Contact.php';
require '../webhook/add_contact.php';

$data = "abcdef jwooten37830@icloud.com 103 Balboa Circle Oak Ridge, TN 37830 (865) 300-4774 John Wooten ";
$account = "jwooten37830@icloud.com";

print "\n* * * * * * * * testContact.php (".date('Y-m-d').") * * * * * * * *\n\n";
print "\n---------------------- Contact with leading junk in data -----------------------\n";
$contact = new Contact($account, $data);

//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n---------------------- Contact with no address -----------------------\n";
$contact = new Contact($account, "Ralph McLeod, rmcleod@testers.com, (888) 555-1212");

//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n---------------------- Contact with no address different phone format -----------------------\n";
$contact = new Contact($account, "Ralph McLeod, rmcleod@testers.com, 888-555-1212");

//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n---------------------- Contact with no address different phone format -----------------------\n";
$contact = new Contact($account, "Ralph O'Toole, rotoole@testers.com, 888.555.1212");

//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n---------------------- Contact with complicated address O name and extended zip Code and phone format -----------------------\n";
$contact = new Contact($account, "Ralph O'Toole, 16234 90th Street NW Alachua, FL 33050-1234 rotoole@testers.com, 8885551212");

//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n---------------------- Contact with leading phone and complicated address and McName -----------------------\n";
$contact = new Contact($account, "(888) 555-1212 Ralph McLeod, 16234 90th Street NW Alachua, FL 33050 rmcleod@testers.com");

//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n---------------------- Contact with phone before numbered stree address -----------------------\n";
$contact = new Contact($account, "(888) 555-1212 106 Crestview Lane Oak Ridge, TN 37830 Ralph Jones charles@testers.com ");

//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n---------------------- Contact with phone before numbered stree address -----------------------\n";
$contact = new Contact("", "   ACCOUNT=<<jwooten37830@icloud.com>> (888) 555-1212 106 Crestview Lane Oak Ridge, TN 37830 Alexander ThePrettyGood alex.pg@testers.com ");
$contact->set_source($account);
//print "Input: ".$contact->get_inputStr()."\n\n";
print $contact;

print "\n ----- Test accessors ---\n\n";
print "get_name: ".$contact->get_name()."\n";
print "get_addr: ".$contact->get_addr()."\n";
print "get_phone: ".$contact->get_phone()."\n";
print "get_email: ".$contact->get_email()."\n";
print "get_source: ".$contact->get_source()."\n";
print "get_acct: ".$contact->get_acct()."\n";
print "get_inputStr: ".$contact->get_inputStr()."\n";


print "result: ".addContactInstance($contact);

print "\n\nEnd of testContact.php\n\n".$contact->get_info()."\n";
?>