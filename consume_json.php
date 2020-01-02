<?php
// JSon request format is :
// // {"userName":"654321@zzzz.com","password":"12345","emailProvider":"zzzz"}
//  
// read JSon input
file_put_contents('/tmp/json-vars.txt',var_export([$_POST,$_GET,$_REQUEST],true));
   
// set json string to php variables
   $userName = $_REQUEST["userName"];
   $password = $_REQUEST["password"];
   $emailProvider = $_REQUEST["emailProvider"];
    
   ini_set("log_errors",1);
   ini_set("error_log","/tmp/php-error.log");
   error_log( "\nRequest Parameters from consume_json.php");
       error_log( "User Name: " .  $userName);
       error_log( "Password: " . $password);
       error_log( "Email Provider: " . $emailProvider);
// create json response
   $responses = array();
   for ($i = 0; $i < 10; $i++) {
       $responses[] = array("name" => $i, "email" => $userName . " " . $password . " " . $emailProvider);
    }
         
// JSon response format is :
// [{"name":"eeee","email":"eee@zzzzz.com"},
// {"name":"aaaa","email":"aaaaa@zzzzz.com"},{"name":"cccc","email":"bbb@zzzzz.com"}]
          
// set header as json
   header("Content-type: application/json");
           
// send response
   echo json_encode($responses);
?>
