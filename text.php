<?php
$phone = $_REQUEST['phone'];
$msg = $_REQUEST['msg'];
$page = $_REQUEST['page'];
$id = $_REQUEST['id'];
$server = "http://localhost:3000/$page/$id?msg=$msg";
echo "<center>";
echo "<h1>Send Text Msg to Phone: $phone</h1>";
//echo "<form type='post' action='$server/$page/$id' >";
echo "<form type='post' action='$server' >";
echo "<textarea name='msg'  cols='40' rows='10'>";
echo "$msg";
echo "</textarea><br /><br />";
echo "<input type='submit' value='Send'  />";
echo "</form>";
echo "</center>";
?>
