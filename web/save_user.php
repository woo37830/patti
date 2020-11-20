<?php

$today = date("D M j G:i:s T Y");
/*
$email = htmlspecialchars($_REQUEST['email']);
$engagemoreid = htmlspecialchars($_REQUEST['engagemoreid']);
$orderid = htmlspecialchars($_REQUEST['orderid']);
$product = htmlspecialchars($_REQUEST['product']);
$status = htmlspecialchars($_REQUEST['status']);
$accountType = htmlspecialchars($_REQUEST['accountType']);
*/
$added = '2020-11-19 14:41:00';
$invoiceid = '987';

require 'config.ini.php';
include 'conn.php';
$table = $config['PATTI_USERS_TABLE'];

$sql = "insert into $table (email,engagemoreid,orderid,invoiceid,product,status,accountType,added) value( 'l@k', '123', 456, 789, 'product-13', 'active', 'test', $added) ";
$result = mysqli_query( $conn, $sql );
if ($result){
	echo json_encode(array('errorMsg' => 'It got inserted.'));
/*	echo json_encode(array(
		'id' => mysql_insert_id() ,
		'email' => $email,
		'engagemoreid' => $engagemoreid,
		'orderid' => $orderid
		'invoiceid' => $invoiceid,
		'product' => $product,
		'status' => $status,
		'accountType' => $accountType,ß
		'added' => $added
	));*/
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
