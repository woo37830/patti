<?php
require 'EmailExtractor.php';

$data = "abcdef jwooten37830@icloud.com 103 Balboa Circle";
$ea = new EmailExtractor($data);

foreach($ea as $key=>$value)
{
  print $value."\n";
}

?>
