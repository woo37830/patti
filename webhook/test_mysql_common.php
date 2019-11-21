<?php
// test the common mysql code

//
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'cancel_account.php';

$inactive = 'inactive';
$active = 'active';
// SET ERROR REPORTING TO DEBUG EASILY
error_reporting(E_ALL);

$user = 'jwooten37830@mac.com';
$json = 'This is some json';
$status = 'success';

//logit($user, $json, $status);
//echo "It worked!\n";

//addUser($user, "thrivecard-345","engagemore-345");
//echo "Adding user also worked\n";
$thrivecartid = 13118877;
$id = getAccountId($thrivecartid);
echo "Obtained engagemoreid = '" . $id . ".\n";

$saved_status = getStatusFor($id);
echo "savedAccountStatus for '" . $id . "' yielded '" . $saved_status . "'\n";

$status = updateAccountStatus($id, $inactive);
echo "updateAccountStatus for '" . $id . "' yielded: '" . $status . "'\n";
$saved_status = getStatusFor($id);
echo "savedAccountStatus for '" . $id . "' yielded '" . $saved_status . "'\n";
$status = updateAccountStatus($id, $active);
echo "updateAccountStatus for '" . $id . "' yielded: '" . $status . "'\n";
$saved_status = getStatusFor($id);
echo "savedAccountStatus for '" . $id . "' yielded '" . $saved_status . "'\n";
?>
