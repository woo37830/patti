<?php
/*
 * Go through json_data.logs and archives of it to extract any lines with the users email
 *
 */

 $result = array();
 $items = array();
 $email = 'jwooten37830@icloud.com';
 $k = 0;
 if ($file = fopen("json-data.log", "r"))
 {
   while(!feof($file))
   {
     $row = fgets($file);
     if (preg_match("/$email/", $row) )
      {
        if( !preg_match("/order.subscription_payment/", $row) )
        {
          $pieces = explode(" ", $row);
          $time = $pieces[0];
          $stuff = $pieces[1];
          $rest = explode(",", $pieces[2]);
          $email = $rest[0];
          $json = array();
          $query = "";
          if (preg_match('/\{(.*?)\}/', $row, $match) == 1) {
            $query = $match[1];
          }
          $json = array('time' => $time, 'email' => $email, 'query' => array('values' => $query) );
          array_push($items, $json);
        }
      }
    }
    fclose($file);
  }

$result["data"] = $items;

echo json_encode($result);

?>
