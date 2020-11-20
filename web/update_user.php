<?php

$id = intval($_REQUEST['id']);
$email = htmlspecialchars($_REQUEST['email']);
$engagemoreid = htmlspecialchars($_REQUEST['engagemoreid']); // TODO not getting inserted
$orderid = intval($_REQUEST['orderid']);	// TODO not getting inserted
$productid = htmlspecialchars($_REQUEST['productid']);
$invoiceid = intval($_REQUEST['invoiceid']);
$status = htmlspecialchars($_REQUEST['status']);
$accountType = htmlspecialchars($_REQUEST['accountType']); // TODO not getting inserted

require 'config.ini.php';
include 'conn.php';
$dbase = $config['PATTI_DATABASE'];
if( $conn = connect($dbase) )
	{
		$datetime = date_create()->format('Y-m-d H:i:s');
		$table = $config['PATTI_USERS_TABLE'];
		$sql = "update $table set email=\"$email\",engagemoreid=\"$engagemoreid\",orderid=\"$orderid\",product=\"$product\", status=\"$status\", accountType=\"$accountType\" where id=$id";
		if ($result = $conn->query($sql))
		{
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
	//));
} else {
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
}
?>
