<?php // mysql_common.php


// COMMON CODE AVAILABLE TO ALL OUR webhook scripts


// SET ERROR REPORTING SO WE CAN DEBUG OUR SCRIPTS EASILY
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

function connect($db) {
// CONNECTION AND SELECTION VARIABLES FOR THE DATABASE
$db_host = "jwooten37830.com"; // PROBABLY THIS IS OK
$db_name = $db;        // GET THESE FROM YOUR HOSTING COMPANY
$db_user = "root";
$db_word = "random1";

// OPEN A CONNECTION TO THE DATA BASE SERVER AND SELECT THE DB
$mysqli = new mysqli($db_host, $db_user, $db_word, $db_name);

// DID THE CONNECT/SELECT WORK OR FAIL?
if ($mysqli->connect_errno)
{
    $err
    = "CONNECT FAIL: "
    . $mysqli->connect_errno
    . ' '
    . $mysqli->connect_error
    ;
    trigger_error($err, E_USER_ERROR);
}
return $mysqli;
}

function logit($user, $json, $my_status)
{
  if( $conn = connect("users_db") )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $sql = "INSERT INTO webhook_log
      ( received
      , email
      , request_json
      , status

      ) VALUES
      ( '$datetime'
      , '$user'
      , '$json'
      , '$my_status'
      )";

      if (!$res = $conn->query($sql))
      {
                 $err
              = "QUERY FAIL: "
              . $sql
              . ' ERRNO: '
              . $mysqli->errno
              . ' ERROR: '
              . $mysqli->error
              ;
              mysqli_close($conn);
              trigger_error($err, E_USER_ERROR);
       }
       else
       {
          mysqli_close($conn);
          return;
       }
     }
}

function addUser($user, $thrivecartid, $engagemoreid)
{
  if( $conn = connect("users_db") )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $sql = "INSERT INTO users
      ( added
      , email
      , thrivecartid
      , engagemoreid
      ) VALUES
      ( '$datetime'
      , '$user'
      , '$thrivecartid'
      , '$engagemoreid'
      )";

      if (!$res = $conn->query($sql))
      {
                 $err
              = "QUERY FAIL: "
              . $sql
              . ' ERRNO: '
              . $mysqli->errno
              . ' ERROR: '
              . $mysqli->error
              ;
              mysqli_close($conn);
              trigger_error($err, E_USER_ERROR);
       }
       else
       {
          mysqli_close($conn);
          return;
       }
     }
}
?>
