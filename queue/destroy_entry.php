<?php
#
# $Author: woo $
# $Date: 2016/03/17 15:29:26 $
# $Revision: 1.3 $
# $Source: /Users/woo/cvsrep/queue/destroy_entry.php,v $
#

$id = intval($_REQUEST['id']);

include 'conn.php';

$sql = "delete from queueEntry where ENTRY_NUMBER=$id";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
