<?php
#
# $Author: woo $
# $Date: 2016/03/17 15:29:26 $
# $Revision: 1.2 $
# $Source: /Users/woo/cvsrep/queue/tests/test_json.php,v $
#
// Database details
$db_server   = 'jwooten37830.com';
$db_username = 'root';
$db_password = 'random1';
$db_name     = 'queue_db';

$db_tablename = 'queueEntry';

$mysqli = new mysqli($db_server, $db_username, $db_password, $db_name);
$query = "SELECT * FROM $db_tablename";

$results = array();

if ($result = $mysqli->query($query)) {

    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    /* free result set */
    $result->free();
}

/* close connection */
$mysqli->close();

/* print json object*/
echo json_encode($results);
?>
