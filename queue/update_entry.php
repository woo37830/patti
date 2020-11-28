<?php
#
# $Author: woo $
# $Date: 2016/03/17 15:29:26 $
# $Revision: 1.4 $
# $Source: /Users/woo/cvsrep/queue/update_entry.php,v $
#

$id = intval($_REQUEST['id']);
$msg = htmlspecialchars($_REQUEST['msg']);

include 'conn.php';

$sql = "update queueEntry set msg='$msg' where ENTRY_NUMBER=$id";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array(
		'msg' => $msg
	));
} else {
	echo json_encode(array('errorMsg'=>'Some update errors occured'));
}
?>
