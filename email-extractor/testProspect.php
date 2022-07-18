<?php
require 'Prospect.php';

$data = "abcdef jwooten37830@icloud.com 103 Balboa Circle Oak Ridge, TN 37830 (865) 300-4774 John Wooten ";

print "\n* * * * * * * * testProspect.php (".date('Y-m-d').") * * * * * * * *\n\n";
print "\n---------------------- Prospect with leading junk in data -----------------------\n";
$prospect = new Prospect($data);

print "Input: ".$prospect->get_inputStr()."\n\n";
print $prospect;

print "\n---------------------- Prospect with no address -----------------------\n";
$prospect = new Prospect("Ralph McLeod, rmcleod@testers.com, (888) 555-1212");

print "Input: ".$prospect->get_inputStr()."\n\n";
print $prospect;

print "\n---------------------- Prospect with no address different phone format -----------------------\n";
$prospect = new Prospect("Ralph McLeod, rmcleod@testers.com, 888-555-1212");

print "Input: ".$prospect->get_inputStr()."\n\n";
print $prospect;

print "\n---------------------- Prospect with no address different phone format -----------------------\n";
$prospect = new Prospect("Ralph O'Toole, rotoole@testers.com, 888.555.1212");

print "Input: ".$prospect->get_inputStr()."\n\n";
print $prospect;

print "\n---------------------- Prospect with complicated address O name and extended zip Code and phone format -----------------------\n";
$prospect = new Prospect("Ralph O'Toole, 16234 90th Street NW Alachua, FL 33050-1234 rotoole@testers.com, 8885551212");

print "Input: ".$prospect->get_inputStr()."\n\n";
print $prospect;

print "\n---------------------- Prospect with leading phone and complicated address and McName -----------------------\n";
$prospect = new Prospect("(888) 555-1212 Ralph McLeod, 16234 90th Street NW Alachua, FL 33050 rmcleod@testers.com");

print "Input: ".$prospect->get_inputStr()."\n\n";
print $prospect;

print "\n---------------------- Prospect with phone before numbered stree address -----------------------\n";
$prospect = new Prospect("(888) 555-1212 106 Crestview Lane Oak Ridge, TN 37830 Ralph Jones charles@testers.com ");

print "Input: ".$prospect->get_inputStr()."\n\n";
print $prospect;

print "\n ----- Test accessors ---\n";
print "get_name: ".$prospect->get_name()."\n";
print "get_addr: ".$prospect->get_addr()."\n";
print "get_phone: ".$prospect->get_phone()."\n";
print "get_email: ".$prospect->get_email()."\n";
print "get_inputStr: ".$prospect->get_inputStr()."\n";

print "\nget_info: ".$prospect->get_info()."\n";
?>
