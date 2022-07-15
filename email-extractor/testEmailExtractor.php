<?php
require 'EmailExtractor.php';

$data = "abcdef jwooten37830@icloud.com 103 Balboa Circle Oak Ridge TN 37830 (865) 300-4774 John Wooten ";
$ea = new EmailExtractor();

$emailList = $ea->extractEmailFromText($data);
foreach($emailList as $key=>$value)
{
  print $value."\n";
}
$phoneList = $ea->extractPhoneFromText($data);
  print $phoneList."\n";

$phoneList = $ea->extractPhoneFromText("866.300.4774");
print $phoneList."\n";

$phoneList = $ea->extractPhoneFromText("5205555542");
print $phoneList."\n";

$phoneList = $ea->extractPhoneFromText("(199) 111-4444");
print $phoneList."\n";

$addrList = $ea->extractAddrFromText($data);
print $addrList."\n";

?>
