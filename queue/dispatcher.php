<?php
#
# $Author: woo $
# $Date: 2016/03/17 15:29:26 $
# $Revision: 1.3 $
# $Source: /Users/woo/cvsrep/queue/deQueue.php,v $
#
    $id = intval($_REQUEST['oid']);
    header("Content-type: application/json");
    header("Cache-Control: no-cache, must-revalidate");
    $result = array();
	$items = array();
	include 'conn.php';
    $sql = "select * from queueEntry WHERE ENTRY_NUMBER = $id";
    $rs = mysqli_query($conn, $sql);
$rs = mysqli_query($conn, $sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error($conn), E_USER_ERROR);
    if ($rs){
        while($row = mysqli_fetch_object($rs)){
            array_push($items, $row);
        }
        $result["data"] = $items;
        echo json_encode($result);
    } else {
       $response_array['errorMsg'] = 'Some errors occurred.';
       $response_array['msg'] = mysqli_error($conn);
       echo json_encode($response_array);
  }

?>
