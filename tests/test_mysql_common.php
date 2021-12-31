<?php

require '../web/conn.php';

echo "Test getting a connection";
$conn = connect('users_db');

if( !isset($conn) ) {
  echo "Did not get connection";
  }

echo "All Done!";
