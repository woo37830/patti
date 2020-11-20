<?php

$datetime = date_create()->format('Y-m-d H:i:s');

$email = htmlspecialchars($_REQUEST['email']);
$engagemoreid = htmlspecialchars($_REQUEST['engagemoreid']); // TODO not getting inserted
$orderid = intval($_REQUEST['orderid']);	// TODO not getting inserted
$productid = htmlspecialchars($_REQUEST['product']);
$invoiceid = intval($_REQUEST['invoiceid']);
$status = htmlspecialchars($_REQUEST['status']);
$accountType = htmlspecialchars($_REQUEST['accountType']); // TODO not getting inserted
/*
$email = 'ralph@testers.com';
$engagemoreid = 12345;
$orderid = 6789;
$productid = 'product-13';
$invoiceid = 123;
$status = 'active';
$accountType = 'test';
*/
$invoiceid = 123;

require 'config.ini.php';
include 'conn.php';

$dbase = $config['PATTI_DATABASE'];
if( $conn = connect($dbase) )
	{
		$datetime = date_create()->format('Y-m-d H:i:s');
		$table = $config['PATTI_USERS_TABLE'];
		$sql = "INSERT INTO $table
		( added
		, email
		, engagemoreid
		, product
		, invoiceid
		, orderid
		, status
		, accountType
		) VALUES
		( '$datetime'
		, '$email'
		, '$engagemoreid'
		, '$productid'
		, '$invoiceid'
		, '$orderid'
		, '$status'
		, '$accountType'
		)";

		if (!$res = $conn->query($sql))
		{
							 $err
						= "QUERY FAIL: "
						. $sql
						. ' ERRNO: '
						. $conn->errno
						. ' ERROR: '
						. $conn->error
						;
						mysqli_close($conn);
						trigger_error($err, E_USER_ERROR);
		 } // end of got an error
		 else
		 {
				mysqli_close($conn);
/*	echo json_encode(array(
		'id' => mysql_insert_id() ,
		'email' => $email,
		'engagemoreid' => $engagemoreid,
		'orderid' => $orderid
		'invoiceid' => $invoiceid,
		'product' => $product,
		'status' => $status,
		'accountType' => $accountType,
		'added' => $added
	));*/
		return;
	} // end of successful insertion

} // end of got connection
else
{
	echo json_encode(array('errorMsg'=>'No connection.'));
}

?>
