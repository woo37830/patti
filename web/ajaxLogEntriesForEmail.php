<?php
/*
 * Go through json_data.logs and archives of it to extract any lines with the users email
 *
 */

 $result = array();
 $items = array();
 $email = 'jwooten37830@icloud.com';
 $k = 0;
 if ($file = fopen("json-data.json", "r"))
 {
   while(!feof($file))
   {
     $row = fgets($file);
     array_push($items, $row);
    }
    fclose($file);
  }

echo json_encode($items);

?>
