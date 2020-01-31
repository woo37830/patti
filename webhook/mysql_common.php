<?php // mysql_common.php


// COMMON CODE AVAILABLE TO ALL OUR webhook scripts


// SET ERROR REPORTING SO WE CAN DEBUG OUR SCRIPTS EASILY
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

function connect($db) {
  require 'config.ini.php';
$db_host = $config['PATTI_DATABASE_SERVER']; // PROBABLY THIS IS OK
$db_name = $db;        // GET THESE FROM YOUR HOSTING COMPANY
$db_user = $config['PATTI_DATABASE_USER'];
$db_word = $config['PATTI_DATABASE_PASSWORD'];

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
  $dbase = $config['PATTI_DATABASE'];
  if( $conn = connect($dbase) )
    {
      $rev = exec('git rev-parse --short HEAD');
      $branch = exec('git rev-parse --abbrev-ref HEAD');

      $datetime = date_create()->format('Y-m-d H:i:s');
      $table = $config['PATTI_LOG_TABLE'];
      $sql = "INSERT INTO $table
      ( received
      , email
      , request_json
      , status
      , commit_hash
      , branch

      ) VALUES
      ( '$datetime'
      , '$user'
      , '$json'
      , '$my_status'
      , '$rev'
      , '$branch'
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

function addUser($user, $engagemoreid, $productid)
{
  $dbase = $config['PATTI_DATABASE'];
  if( $conn = connect($dbase) )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $table = $config['PATTI_USERS_TABLE'];
      $sql = "INSERT INTO $table
      ( added
      , email
      , engagemoreid
      , product
      ) VALUES
      ( '$datetime'
      , '$user'
      , '$engagemoreid'
      , '$productid'
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
  $table = $config['PATTI_USERS_TABLE'];

  $sql = " UPDATE $table SET status='" . $new_status . "' WHERE engagemoreid = " . $accountid ;
  $status = 'Failed';
  $dbase = $config['PATTI_DATABASE'];

  if( $conn = connect($dbase) )
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
  $dbase = $config['PATTI_DATABASE'];

  if( $conn = connect($dbase) )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $table = $config['PATTI_USERS_TABLE'];

      $query = "SELECT status FROM $table WHERE engagemoreid = " . $accountid;

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
  $dbase = $config['PATTI_DATABASE'];

  if( $conn = connect($dbase) )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $table = $config['PATTI_USERS_TABLE'];

      $query = "SELECT engagemoreid FROM $table WHERE email = '$email' ";

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
  $dbase = $config['PATTI_DATABASE'];

  if( $conn = connect($dbase) )
    {
      $datetime = date_create()->format('Y-m-d H:i:s');
      $table = $config['PATTI_USERS_TABLE'];

      $query = "SELECT product FROM $table WHERE email = '$email' ";
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
  $table = $config['PATTI_USERS_TABLE'];

  $sql = " UPDATE $table SET product = '" . $new_product . "' WHERE engagemoreid = " . $accountid ;
  $status = 'Failed';
  $dbase = $config['PATTI_DATABASE'];

  if( $conn = connect($dbase) )
  {
    if (mysqli_query($conn, $sql))
    {
      $status = 'Succeeded';
    }
    else
    {
      status =  "FAILED: " . mysqli_error($conn);
    }
    mysqli_close($conn);
  }
  return $status;
}

?>
