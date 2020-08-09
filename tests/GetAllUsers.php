<?php
// Test the addContactNote
session_start(); //don't forget to do this
$location = "/patti/tests/GetAllUsers.php";
if( !isset($_SESSION['auth']) ) {
  require('fancyAuthentication.php');
}


?>
<html>
<head>
  <style type="text/css" media="screen">
html {
//      background-color: #c8dcff;
 }

body
{
    color:#404040;
#    background-color: powderblue;
    font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
}

#page
{
  position: relative;
  min-height: 100%;
}

#content
{
  padding-bottom: 1.5rem; /* Footer height */
}

footer
{
  width: 100%;
  position: absolute;
  bottom: 0;
  padding-top: 3px;
  background-color: powderblue;
  text-align: center;
  height: 1.5rem;
  font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
  font-style: italic;
}

table {
  border-collapse: collapse;
  border: 1px solid black;
}

#tests table {
  border: 1px;
}

#tests tr th {
  border: 1px solid #000;
  padding: 10px;
}
#tests tr td {
  border: 1px solid #000;
  padding: 10px;
}



/* Zebra stripe */
#tests tr:nth-child(odd) {
  background: #f4f4f4;
}

</style>
</head>
<body>
    <center>
  <br />
  <h1>EngagemoreCRM Users</h1>
  <div id='page'>
    <div id='content'>
<?php
require '../webhook/add_contact.php';
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';

$today = date("D M j G:i:s T Y");
echo "<center>$today<br /><hr />";

  if( isset($_REQUEST['id'])) {
      echo "<h2>submit id = ".$_REQUEST['id']."</h2>";
      echo "<br /><a href='./GetAllUsers.php' >Back</a>";
  } else {

?>
      <table id='tests'>
        <thead>
          <tr><th>#</th><th>ID</th><th>Email</th><th>EngagemoreID</th><th>Order</th><th>Invoice</th><th>Product</th><th>Status</th><th>Since</th></tr>
        </thead>
        <tbody>
<?php
$users = getAllUsers();
$k = 1;
foreach($users as $user){
//    print_r($user);
    echo "<tr><td>" . $k++ . "</td><td><a href='./GetAllUsers.php?id=".$user['id']."'>".$user['id']."</a>".
      "</td><td>".$user['email'].
      "</td><td>".$user['engagemoreid'].
      "</td><td>".$user['orderid'].
      "</td><td>".$user['invoiceid'].
      "</td><td>".$user['product'].
      "</td><td>".$user['status'].
      "</td><td>".$user['added'].
      "</td></tr>";
}


echo "</tbody></table>";
  } // end of showing users
echo "</center><footer>";
include '../webhook/git-info.php';
echo "</footer></div></div></div></body></html>";

?>
</body>
</html>
