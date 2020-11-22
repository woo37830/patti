<?php

require 'config.ini.php';
include 'conn.php';

$dbase = $config['PATTI_DATABASE'];
if( $conn = connect($dbase) )
	{
		$table = $config['PATTI_USERS_TABLE'];
		$id = intval($_REQUEST['id']);

		$sql = "delete from $table where id=$id";
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
						echo json_encode(array('errorMsg'=>'Some errors occured.'));
						return;
				//		trigger_error($err, E_USER_ERROR);
		 } // end of got an error
		 else
		 {
				mysqli_close($conn);
		  	echo json_encode(array('success'=>true));
			}
	}
	else {
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
?>
