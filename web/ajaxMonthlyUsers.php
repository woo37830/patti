<?php

function getMonthlyUsers($mon) {
	require 'config.ini.php';
	require_once 'conn.php';
	//$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	//$offset = ($page-1)*$rows;

	$dbase = $config['PATTI_DATABASE'];
	if( $conn = connect($dbase) )
	{
		$datetime = date_create()->format('Y-m-d H:i:s');
		$table = $config['PATTI_USERS_TABLE'];
		$sql = "SELECT email, engagemoreid, invoiceid, product, added,MONTH(added)  as 'added. MONTH' " .
			"FROM `$table` WHERE MONTH(added) =  " . $mon . " AND status = 'active'";

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
$mon = isset($_REQUEST['mon']) ? intval($_REQUEST['mon']) : (int)date("m");

$results = array();
$result["month"] = $mon;
$result["data"] = getMonthlyUsers($mon);
echo json_encode($result);
?>
