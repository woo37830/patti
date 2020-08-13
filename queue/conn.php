<?php
require 'config.ini.php';
$conn = new mysqli($config['QUEUE_DATABASE_SERVER'],$config['QUEUE_DATABASE_USER'],$config['QUEUE_DATABASE_PASSWORD'],$config['QUEUE_DATABASE']);
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
	error_log("FAILURE: $conn -> connect_error");
  exit();
}

?>
