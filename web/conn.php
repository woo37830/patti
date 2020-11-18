<?php
require 'config.ini.php';

$conn = new mysqli($config['PATTI_DATABASE_SERVER'],$config['DATABASE_USER'],$config['DATABASE_PASSWORD'],$config['PATTI_DATABASE']);
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}

?>
