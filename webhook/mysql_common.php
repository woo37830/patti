<?php // mysql_common.php


// COMMON CODE AVAILABLE TO ALL OUR webhook scripts


// SET ERROR REPORTING SO WE CAN DEBUG OUR SCRIPTS EASILY
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

function connect($db) {
  require 'config.ini.php';
$db_host = "jwooten37830.com"; // PROBABLY THIS IS OK
$db_name = $db;        // GET THESE FROM YOUR HOSTING COMPANY
$db_user = "root";
$db_word = $config['RAILS_PASSWORD'];

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
      $sql = "INSERT INTO logs
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

function addUser($user, $engagemoreid)
{
  if( $conn = connect("users_db") )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $sql = "INSERT INTO users
      ( added
      , email
      , engagemoreid
      ) VALUES
      ( '$datetime'
      , '$user'
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

function updateAccountStatus($accountid, $new_status)
{ // Set the status in the users table to show it is inactive for the $accountid.
  $sql = " UPDATE users SET status='" . $new_status . "' WHERE engagemoreid = " . $accountid ;
  $status = 'Failed';
  if( $conn = connect("users_db") )
  {
    if (mysqli_query($conn, $sql))
    {
      $status = 'Succeeded';
    }
    else
    {
      echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
  }
  return $status;
}

function getStatusFor( $accountid ) {
  if( $conn = connect("users_db") )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $query = "SELECT status FROM users WHERE engagemoreid = " . $accountid;

     $result = mysqli_query( $conn, $query);
     $table = mysqli_fetch_all($result,MYSQLI_ASSOC);
     $result -> close();
     $conn->close();
     return $table[0]['status'];
     }
    return 'inactive';
}

function getAccountId($email)
{ // Get the Engagemore(AllClients) engagemoreid from the users database
  // given the email or the thrivecart id
  $value = -1;
  if( $conn = connect("users_db") )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $query = "SELECT engagemoreid FROM users WHERE email = '$email' ";

       $result = mysqli_query( $conn, $query);
       $table = mysqli_fetch_all($result,MYSQLI_ASSOC);
       if( !empty( $table[0] ) )
       {
         $value = (int)$table[0]['engagemoreid'];
       }
       $result -> close();
       $conn->close();
     }
    return $value;
}
function getProductFor( $email ) {
  $value = -1;
  if( $conn = connect("users_db") )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $query = "SELECT product FROM users WHERE email = '$email' ";
      echo "\nquery = '$query'\n";

       $result = mysqli_query( $conn, $query);
       $table = mysqli_fetch_all($result,MYSQLI_ASSOC);
       if( !empty( $table[0] ) )
       {
         $value = $table[0]['product'];
         echo "\nvalue = $value\n";
       }
       $result -> close();
       $conn->close();
     }
    return $value;
}

function updateProduct($accountid, $new_product)
{ // Set the status in the users table to show it is inactive for the $accountid.
  $sql = " UPDATE users SET product = '" . $new_product . "' WHERE engagemoreid = " . $accountid ;
  $status = 'Failed';
  if( $conn = connect("users_db") )
  {
    if (mysqli_query($conn, $sql))
    {
      $status = 'Succeeded';
    }
    else
    {
      echo "\nError updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
  }
  echo "\nUpdated product for $accountid to $new_product\n";
  return $status;
}

?>
