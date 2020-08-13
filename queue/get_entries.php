<?php
#
# $Author: woo $
# $Date: 2016/03/17 15:29:26 $
# $Revision: 1.3 $
# $Source: /Users/woo/cvsrep/queue/get_entries.php,v $
#
header("Content-type: application/json");
header("Cache-Control: no-cache, must-revalidate");
  require 'config.ini.php';
	include 'conn.php';
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$result = array();
	$items = array();
    $user = htmlspecialchars($_POST["uName"]);
    $role = htmlspecialchars($_POST["uRole"]);
    $user = "";
    $role = "mgr";
    if( $user != "" || $role != "" ) {
        $sql = "select * from " . $config['QUEUE_ENTRY_TABLE'] . " WHERE ENTRY_STATUS = 'ACTIVE'  AND ( (QUEUED_FOR_USERID = '$user') OR (QUEUED_FOR_ROLE = '$role' ) ) ";
        error_log("sql = $sql");
    if( $rs = mysqli_query($conn, $sql) ) {
      error_log( "get_entries: Got connection");
	    while($row = $rs -> fetch_object()){
		    array_push($items, $row);
	    }
			$rs -> free_result();
	 } else {
     error_log("get_entries.php, error: ".$conn->error);
     $conn -> close();
   }
		$conn -> close();
	}
	$result["data"] = $items;
  error_log("returning ".json_encode($result));
	echo json_encode($result);

?>
