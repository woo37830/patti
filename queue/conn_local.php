<?php
#
# $Author: woo $
# $Date: 2016/03/17 15:29:26 $
# $Revision: 1.3 $
# $Source: /Users/woo/cvsrep/queue/conn_local.php,v $
#
require 'config.ini.php';
$conn = new mysqli('127.0.0.1','root',$config['QUEUE_DATABASE_PASSWORD'],'queue_db');
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

?>
