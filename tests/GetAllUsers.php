<?php

  session_start(); //don't forget to do this
  $location = "/patti/tests/GetAllUsers.php";

  require('fancyAuthentication.php');



?><html>

<head>
  <style type="text/css" media="screen">
html {
/*      background-color: #c8dcff; */
 }

body
{
    color:#404040;
/*   background-color: powderblue; */
    font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
}

#page
{
  position: relative;
  min-height: 100%;
}

#content
{
  width: 90vw;
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
}

#tests table, th, td {
  width: 90vw;
  border: 1px solid black;
  padding: 5px 5px 5px 5px;
}
#tests table, tr th {
  border: 1px solid #000;
  padding: 10px;
}

#user table, th, td {
  width: 50vw;
  border: 1px solid black;
  padding: 5px 5px 5px 5px;
}


/* Zebra stripe */
#tests tr:nth-child(odd) {
  background: #f4f4f4;
}

/* Zebra stripe */
#user tr:nth-child(odd) {
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
$back = './GetAllUsers.php';
if( isset($_REQUEST['back']) ) {
  $back = $_REQUEST['back'];
}
$today = date("D M j G:i:s T Y");
echo "<center>$today<br /><hr />";
function showUserForm( $user, $back ) {
  echo "<form type='POST' >";
  echo "<table id='user' >";
  echo "<thead>";
  echo "<tr><th>Field</th><th>Value</th></tr>";
  echo "</thead><tbody>";
  echo "<tr><td>Email</td><td>".$user['email']."</td></tr>".
  "<tr><td>EngagemoreID</td><td><input type=\"text\" name=\"engagemoreid\" value=\"".$user['engagemoreid']."\" /></td></tr>".
  "<tr><td>OrderID</td><td><input type=\"text\" name=\"orderid\" value=\"".$user['orderid']."\" </td></tr>".
  "<tr><td>InvoiceID</td><td><input type=\"text\" name=\"invoiceid\" value=\"".$user['invoiceid']."\" </td></tr>".
  "<tr><td>ProductID</td><td><input type=\"text\" name=\"product\" value=\"".$user['product']."\" </td></tr>".
  "<tr><td>Status</td><td><input type=\"text\" name=\"status\" value=\"".$user['status']."\" </td></tr>".
  "<tr><td>Added</td><td>".$user['added']."</td></tr></tbody></table>";

  echo "<br /><input type='submit' name='Update' value='Update'/>";
  echo "&nbsp;&nbsp;<input type='submit' name='Delete' value='Delete' />";
  echo "</form>";
  echo "<br /><a href='".$back."' >Back</a>";

}
  if( isset($_REQUEST['id'])) {
      echo "<h2>id: ".$_REQUEST['id']."</h2>";
      $user = getUser($_REQUEST['id'])[0];
      showUserForm( $user , $back);
    } else
    if( isset($_REQUEST['Delete']) ) {
      echo "<h2>Are You Sure (Y/N)?</h2><br /><br />";
      echo "<form type='POST' >";
      echo "<input type='submit' name='YES' value='YES' />";
      echo "&nbsp;&nbsp;<input type='submit' name='NO' value='NO' />";
      echo "</form>";
      echo "<br /><a href='".$back."' >Back</a>";
    } else
      if( isset($_REQUEST['YES']) ) {
        echo "<h2>This suckers GONE!</h2>";
        echo "<br /><a href='".$back."' >Back</a>";
    } else
     if( isset($_REQUEST['Update']) ) {
       echo "<h2>This suckers UPDATED!</h2>";
       echo "<br /><a href='".$back."' >Back</a>";
     } else
     if( isset($_REQUEST['email']) ) {
       $user = getUserByEmail( $_REQUEST['email'] );
       showUserForm( $user, $back );
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
echo "</footer></div></div></div>";

?>
</body>
</html>
