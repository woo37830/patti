<?php

// include mylib.php (contains Logging class)
include('mylib.php');

// Logging class initialization
$log = new Logging();

// set path and name of log file (optional)
$log->lfile('/tmp/mylog.txt');

// write message to the log file
$log->lwrite('Test message1');
$log->lwrite('Test message2');
$log->lwrite('Test message3');

// close log file
$log->lclose();

?>