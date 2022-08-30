<?php

function getMonthlyUsers($mon, $year) {
	require 'config.ini.php';
	require_once 'conn.php';
	//$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	//$offset = ($page-1)*$rows;

	$dbase = $config['PATTI_DATABASE'];
	if( $conn = connect($dbase) )
	{
		$table = $config['PATTI_USERS_TABLE'];
		$sql = "SELECT * FROM $table WHERE status = 'active' AND YEAR(added) = $year AND MONTH(added) =  $mon ORDER BY id";

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
