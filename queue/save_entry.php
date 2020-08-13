<?php
#
# $Author: woo $
# $Date: 2016/03/17 15:29:26 $
# $Revision: 1.3 $
# $Source: /Users/woo/cvsrep/queue/save_entry.php,v $
#

$process = htmlspecialchars($_REQUEST['process']);
$activity = htmlspecialchars($_REQUEST['activity']);
$application = htmlspecialchars($_REQUEST['application']);
$creator = htmlspecialchars($_REQUEST['uName']);
$org = htmlspecialchars($_REQUEST['org']);;
$status = "Active";
$queued_for_role = "mgr";
date_default_timezone_set('America/New_York');
$today = date("Y-m-d H:i:s");

include 'conn.php';

$sql = "insert into queueEntry(ORGANIZATION,OWNER,QUEUE_NAME,ENTRY_STATUS,QUEUED_FOR_ROLE,BUSINESS_PROCESS,ACTIVITY,CREATION_DATE,JOB_ID) values('$org','$creator','$application','$status','$queued_for_role','$process','$activity','$today',0)";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array(
		'entry' => mysql_insert_id()
	));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured->'+mysql_errno($conn)+': '+mysql_error($conn)));
}
?>
