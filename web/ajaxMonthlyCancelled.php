<?php

function getCancelledUsers($mon) {
	require 'config.ini.php';
	require_once 'conn.php';
	//$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	//$offset = ($page-1)*$rows;

	$dbase = $config['PATTI_DATABASE'];
	if( $conn = connect($dbase) )
	{
		$datetime = date_create()->format('Y-m-d H:i:s');
		$table = $config['PATTI_LOG_TABLE'];
		$users = $config['PATTI_USERS_TABLE'];
		$sql = "SELECT t.email, u.engagemoreid, received,MONTH(received)  FROM $table t "
		. " JOIN $users u ON u.email = t.email "
		. " WHERE MONTH(received) =  " . $mon . " AND t.status LIKE '%Subscription_cancelled, result: Succeeded%'";

		$rs = $conn -> query( $sql );

		$items = array();
		while($row = mysqli_fetch_array($rs)){
		    //echo json_encode($row);
			array_push($items, $row);
		}
	}
	else {
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
		return;
	}

	return $items;
}
$mon = isset($_REQUEST['month']) ? intval($_REQUEST['month']) : (int)date("m");

$results = array();
$result["month"] = $mon;
$result["data"] = getCancelledUsers($mon);
echo json_encode($result);
?>
