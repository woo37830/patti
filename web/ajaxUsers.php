<?php

/*
 * Provide ajax access to get_users
 *
*/

require './get_users.php';

$results = array();
$result["data"] = getUsers();
  header("Content-type: application/json");
  header("Cache-Control: no-cache, must-revalidate");

echo json_encode($result);

?>
