<?php
// Read the ajax-data.log file line by line and, where possible,
// extract the users email and the time of the log entry.
// Then convert that time into the same format as the mysql date-time field.
// Locate that record in the mysql users_db database logs table,
// and replace the request field with the json-data string.
//
function getRowMatchingJsonFile( $email, $date, $json_data) {
  require_once 'conn.php';
  require '../webhook/config.ini.php';
  $dbase = $config['PATTI_DATABASE'];
  if( $conn = connect($dbase) )
    {
  //    $datetime = date_create()->format('Y-m-d H:i:s');
      $table = $config['PATTI_LOG_TABLE'];
      $sql = "SELECT * FROM `$table` WHERE email = '$email' and received = '$date'";
      //echo $sql . "\n";
      $rs = $conn -> query( $sql );

  		$items = array();
  		while($row = mysqli_fetch_array($rs)){
  		    //echo json_encode($row);
  			array_push($items, $row);
  		}
  	}
  	else {
  		echo json_encode(array('errorMsg'=>'Some errors occured.'));
  		return;
  	}

    return $items;

}

function updateRequestJsonForId( $id, $jsonData ) {
  require_once 'conn.php';
  require '../webhook/config.ini.php';
  $dbase = $config['PATTI_DATABASE'];
  if( $conn = connect($dbase) ) {
  //    $datetime = date_create()->format('Y-m-d H:i:s');
      $table = $config['PATTI_LOG_TABLE'];
      $sql = "UPDATE `$table` SET request_json = '$jsonData'   WHERE id = $id";
      if(mysqli_query($conn, $sql)) {
        echo "id: $id was updated successfully.";
        return true;
      } else {
          echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
          return false;
      }
      // Close connection
      mysqli_close($conn);
    }
    else {
  		echo json_encode(array('errorMsg'=>'Some errors occured.'));
  		return false;
  	}

}

$handle = fopen("../webhook/json-data.log", "r");
$kount = 0;
$successful = 0;
if ( $handle ) {
  while (( $line = fgets($handle)) !== false ) {
    $kount++;
    // process the line read.
    if( true ) {
      //echo "$kount) ".$line."\n";
      preg_match("/\[(.*?)\] \(thrivecart\) (.*),{(.*)}$/",$line,$m);
      //print_r($m);
      // Now, $m[1] is the date in format 03/Aug/2020:16:22:55
      //      $m[2] is the email of the user who's entry is logged
      //      $m[3] is the entire json string without the surrounding { }
      $timestamp = date_create_from_format("d/M/Y:H:i:s", $m[1]);
      $sqlDate = date_format( $timestamp, "Y-m-d H:i:s" );
      $result = getRowMatchingJsonFile($m[2], $sqlDate, $m[3]);
      if( sizeof($result) > 0 && stripos($result[0][3],"See json logs") !== false ) {
        print_r( $result );
        print_r( $m[3] );
        if( updateRequestJsonForId($result[0][0], "{$m[3]}") ) {
          $successful++;
        };
      }
    }
  }
  fclose( $handle );
} else {
  // error opening the file
  echo "Error opening file!\n";
}
echo "\nTotal lines read = $kount\nTotal updated = $successful\n";
?>
