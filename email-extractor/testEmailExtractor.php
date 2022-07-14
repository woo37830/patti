<?php
require 'EmailExtractor.php';

$data = "abcdef jwooten37830@icloud.com 103 Balboa Circle (865) 300-4774 John Wooten";
$ea = new EmailExtractor();

$emailList = $ea->extractEmailFromText($data);
foreach($emailList as $key=>$value)
{
  print $value."\n";
}
$phoneList = $ea->extractPhoneFromText($data);
  print $phoneList."\n";


?>
