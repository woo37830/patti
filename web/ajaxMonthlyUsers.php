<?php

function getMonthlyUsers($mon, $year) {
	require 'config.ini.php';
	require_once 'conn.php';
	//$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	//$offset = ($page-1)*$rows;

	$dbase = $config['PATTI_DATABASE'];
	if( $conn = connect($dbase) )
	{
		$datetime = date_create()->format('Y-m-d H:i:s');
		$table = $config['PATTI_USERS_TABLE'];
		$sql = "SELECT email, engagemoreid, invoiceid, product, added, MONTH(added),YEAR(added)  as 'added ' " .
			"FROM `$table` WHERE MONTH(added) =  " . $mon . " AND YEAR(added) = ". $year . " AND status = 'active'";

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
$year = isset($_REQUEST['year']) ? intval($_REQUEST['year']) : (int)date("Y");
$results = array();
$result["month"] = $mon;
$result["year"] = $year;
$result["data"] = getMonthlyUsers($mon, $year);
echo json_encode($result);
?>
