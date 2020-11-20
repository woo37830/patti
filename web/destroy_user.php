<?php

$id = intval($_REQUEST['id']);

require 'config.ini.php';
include 'conn.php';

$table = $config['PATTI_USERS_TABLE'];

$sql = "delete from $table where id=$id";
$result =mysqli_query( $conn, $sql );
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
