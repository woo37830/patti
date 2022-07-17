<?php
require 'Prospect.php';

$data = "abcdef jwooten37830@icloud.com 103 Balboa Circle Oak Ridge, TN 37830 (865) 300-4774 John Wooten ";

print "\n\nInput Email text body: ".$data."\n\n";

$ea = new Prospect("");

$emailList = $ea->extractEmailFromText($data);
foreach($emailList as $key=>$value)
{
  print "\nEmail: ".$value."\n";
}
$phoneList = $ea->extractPhoneFromText($data);
  print "Phone: ".$phoneList."\n";

//$phoneList = $ea->extractPhoneFromText("866.300.4774");
//print $phoneList."\n";

//$phoneList = $ea->extractPhoneFromText("5205555542");
//print $phoneList."\n";

//$phoneList = $ea->extractPhoneFromText("(199) 111-4444");
//print $phoneList."\n";

$addrList = $ea->extractAddrFromText($data);
print "Address: ".$addrList[0]."\n";
//foreach($addrList as $key=>$value)
//{
//  print $value."\n";
//}
$names = $ea->extractNameFromText($data);
print "Name: ".$names."\n";
//foreach($names as $key=>$value)
//{
//  print $value."\n";
//}
print "\n---------------------- Prospect -----------------------\n";
$prospect = new Prospect($data);

print $prospect;

print "\n---------------------- Prospect -----------------------\n";
$prospect = new Prospect("Ralph McLeod, rmcleod@testers.com, (888) 555-1212");

print $prospect;

print "\n---------------------- Prospect -----------------------\n";
$prospect = new Prospect("Ralph McLeod, rmcleod@testers.com, 888-555-1212");

print $prospect;

print "\n---------------------- Prospect -----------------------\n";
$prospect = new Prospect("Ralph O'Toole, rotoole@testers.com, 888.555.1212");

print $prospect;

print "\n---------------------- Prospect -----------------------\n";
$prospect = new Prospect("Ralph O'Toole, 16234 90th Street NW Alachua, FL 33050-1234 rotoole@testers.com, 8885551212");

print $prospect;

print "\n---------------------- Prospect -----------------------\n";
$prospect = new Prospect("(888) 555-1212 Ralph McLeod, rmcleod@testers.com");

print $prospect;

print "\n---------------------- Prospect -----------------------\n";
$prospect = new Prospect("(888) 555-1212 106 Crestview Lane Oak Ridge, TN 37830 charled@testers.com Ralph Jones ");

print $prospect;

?>
