<?php
/*
 * Go through json_data.logs and archives of it to extract any lines with the users email
 *
 */

 $result = array();
 $items = array();
 $email = 'jwooten37830@icloud.com';
 $k = 0;
 $comma = "";
 $outfile = fopen("json-data.txt",'w');
 fwrite($outfile, "{");

 if ($file = fopen("json-data.log", "r"))
 {
   while(!feof($file))
   {
     $row = fgets($file);
     if( strlen($row) < 5 ) {
       continue;
     }
     $first = strpos($row, ",");
     if( $first === 0 or $first == -1 ) {
       continue;
     }
     if( $k++ > 0 )
     {
       $comma = ",";
     }
     $outstr = $comma . "\"row\": " . substr($row, $first+1);
     fwrite($outfile, $outstr);
    }
    fclose($file);
    fwrite($outfile, "}");
    fclose($outfile);
  }

$result["data"] = "Wrote out $k lines";

echo json_encode($result);

?>
