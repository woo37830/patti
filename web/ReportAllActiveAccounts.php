<?php

//An example JSON string.
require './get_all_accounts.php';

$jsonString = json_encode(getAccounts());
//echo $jsonString;
//Decode the JSON and convert it into an associative array.
$jsonDecoded = json_decode($jsonString, true);
$accounts = $jsonDecoded['accounts'];
//var_dump($accounts);
//Give our CSV file a name.
$csvFileName = 'example.csv';

//Open file pointer.
$fp = fopen($csvFileName, 'w');

//Loop through the associative array.
foreach($accounts as $row){
    foreach($row as $key => $val) {
    //Write the row to the CSV file.
        fputcsv($fp, $row[$key]);
    }
}
//Finally, close the file pointer.
fclose($fp);