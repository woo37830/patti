<?php

$id = intval($_REQUEST['id']);
$email = htmlspecialchars($_REQUEST['email']);
$engagemoreid = intval($_REQUEST['engagemoreid']);
$orderid = intval($_REQUEST['orderid']);
$product = htmlspecialchars($_REQUEST['product']);
$status = htmlspecialchars($_REQUEST['status']);
$accountType = htmlspecialchars($_REQUEST['accountType']);

require 'config.ini.php';
include 'conn.php';
$table = $config['PATTI_USERS_TABLE'];
$sql = "update $table set email=\"$email\",engagemoreid=\"$engagemoreid\",orderid=\"$orderid\",product=\"$product\", status=\"$status\", accountType=\"$accountType\" where id=$id";
$result = @mysql_query($sql);
if ($result){
/*	echo json_encode(array(
		'id' => $id,
		'email' => $email,
		'engagemoreid' => $engagemoreid,
		'orderid' => $orderid,
		'product' => $product,
		'status' => $status,
		'accountType' => $accountType
		*/
		echo "Record has been updated successfully.";
	));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
