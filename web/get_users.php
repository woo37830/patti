<?php
require 'config.ini.php';
require 'conn.php';
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	//$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	//$offset = ($page-1)*$rows;
	$result = array();

	$dbase = $config['PATTI_DATABASE'];
	if( $conn = connect($dbase) )
	{
		$datetime = date_create()->format('Y-m-d H:i:s');
		$table = $config['PATTI_USERS_TABLE'];
		$sql = "SELECT * FROM `$table`";

		$rs = $conn -> query( $sql );

		$items = array();
		while($row = mysqli_fetch_array($rs)){
		    //echo json_encode($row);
			array_push($items, $row);
		}

		$result["data"] = $items;
      header("Content-type: application/json");
      header("Cache-Control: no-cache, must-revalidate");
		}
	else {
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
		return;
	}
	echo json_encode($result);

?>
