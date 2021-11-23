<?php
// Test the addContactNote


function getAUser( $id )
{
  require '../web/config.ini.php';
  require '../webhook/mysql_common.php';

  $dbase = $config['PATTI_DATABASE'];

  if( $conn = connect($dbase) )
  {
    $datetime = date_create()->format('Y-m-d H:i:s');
    $table = $config['PATTI_USERS_TABLE'];

    $query = "SELECT * FROM $table where id = $id";
    $result = $conn->query($query);
    $row = $result -> fetch_assoc();

    // Free result set
    $result -> free_result();

    $conn -> close();   }
  return $row;
}

$today = date("D M j G:i:s T Y");
$id = '56';
$user = getAUser($id);

echo "\n  ".$user['email']."\n";

?>
