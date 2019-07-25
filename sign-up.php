<?php
file_put_contents('/tmp/sign-up.txt',var_export([$_POST,$_GET,$_REQUEST],true));
echo "Thanks!";
//echo "Thanks! <br />We received your sign up information.<br/>and you should receive an email at:<br/>$_REQUEST['email']<br />soon!";
?>
