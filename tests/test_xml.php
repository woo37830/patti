<?php
$result_xml = '<?xml version="1.0"?>
<results>
  <message>Success</message>
  <accountid>15631</accountid>
  <apikey>A34234B343</apikey>
</results>';
echo "$result_xml\n";

$xml = simplexml_load_string($result_xml);
$account_id = (string)$xml->accountid;
echo "account_id = '$account_id'\n";
echo "All Done!";
?>
