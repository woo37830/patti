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
#home {
  align: auto;
  float: right;
  margin-right: 10%;
}

</style>
</head>
<body>
  <a href='./GetAllUsers.php?new=true' id='home'>Add New User</a>
  <a href='./index.php'>Back</a>
    <center>
  <br />
  <h1>EngagemoreCRM Users</h1>
  <div id='page'>
    <div id='content'>
<?php
//require '../webhook/add_contact.php';
require 'config.ini.php';
require 'mysql_common.php';
require 'utilities.php';
$back = './GetAllUsers.php';
if( isset($_REQUEST['back']) ) {
  $back = $_REQUEST['back'];
}
$today = date("D M j G:i:s T Y");
echo "<center>$today<br /><hr />";
function selected( $option, $type ) {
  return ($option == $type ) ? "selected" : "";
}
function productOptions($product) {
  require '../webhook/product_data.php';
  $options = "";
  foreach ($products as $key => $value) {
  $options .=  "<option value=\"$key\" ".selected( $product, $key).">$key</option>";
  }
  return $options;
}
function showUserForm( $user, $back ) {
  date_default_timezone_set('America/New_York');
  $today = date("Y-m-d H:i:s");

  $s = isset($user['accountType']) ? $user['accountType'] : "real";
  $t = isset($user['status']) ? $user['status'] : "active";
  $p = isset($user['product']) ? $user['product'] : "product-13";
  $email = isset($user['email']) ? $user['email'] : "";
  $readOnly = isset($user['email']) ? "readonly" : "";
  $engagemoreId = isset($user['engagemoreid']) ? $user['engagemoreid'] : "";
  $orderId = isset($user['orderid']) ? $user['orderid'] : "";
  $invoiceId = isset($user['invoiceid']) ? $user['invoiceid'] : "";
  $added = isset($user['added']) ? $user['added'] : $today;
  echo "<form type='POST' >";
  echo "<table id='user' >";
  echo "<thead>";
  echo "<tr><th>Field</th><th>Value</th></tr>";
  echo "</thead><tbody>";
  echo "<tr><td>Email</td><td><input type=\"text\" name=\"email\" ".$readOnly." size=\"35\" value=\"".$email."\" /></td></tr>".
  "<tr><td>EngagemoreID</td><td><input title=\"Important that this match CRM Account\" type=\"text\" name=\"engagemoreid\" value=\"".$engagemoreId."\" /></td></tr>".
  "<tr><td>OrderID</td><td><input type=\"text\" name=\"orderid\" value=\"".$orderId."\" </td></tr>".
  "<tr><td>InvoiceID</td><td><input type=\"text\" name=\"invoiceid\" value=\"".$invoiceId."\" </td></tr>".
  "<tr><td>ProductID</td><td><select name=\"product\" >".
    productOptions($p) ." </td></tr>".
  "<tr><td>Status</td><td><select name=\"status\">".
    "<option value=\"active\" ".selected("active", $t).">active</option>".
    "<option value=\"inactive\" ".selected("inactive", $t).">inactive</option> </td></tr>".
  "<tr><td>Type</td><td><select name=\"accountType\">" .
    "<option value=\"test\" ".selected("test", $s).">test</option>".
    "<option value=\"real\" ".selected("real", $s).">real</option>".
    "<option value=\"special\" ".selected("special", $s).">special</option></td></tr>".
  "<tr><td>Added</td><td>".$added."</td></tr></tbody></table>";
  if ( isset($user['email'])) {
    echo "<br /><input type='submit' name='Update' value='Update'/>";
    echo "&nbsp;&nbsp;<input type='submit' name='Delete' value='Delete' />";
  } else {
    echo "<br /><input type='submit' name='add' value='Add'/>";
  }
  echo "</form>";
  echo "<br /><a href='".$back."' >Back</a>";

}
 if( isset($_REQUEST['add']) ) {
// TODO - check for ommitted fields
// TODO - Add accountType to addUser and get fields?
   addUser($_REQUEST['email'], $_REQUEST['engagemoreid'],
    $_REQUEST['product'], $_REQUEST['invoiceid'], $_REQUEST['orderid']);
    echo "<h2>This ".$_REQUEST['email']." has been added!</h2>";
    echo "<br /><a href='".$back."' >Back</a>";
 }
 else {
  if( isset($_REQUEST['id'])) {
      $id = $_REQUEST['id'];
      $user = getUser($id);
      echo "<h2>id: ".$id."</h2>";
      showUserForm( $user , $back);
    } else
    if( isset($_REQUEST['Delete']) ) {
      echo "<h2>Are You Sure (Y/N)?</h2><br /><br />";
      echo "<form type='POST' >";
      echo "<input type='hidden' name='email' value='".$_REQUEST['email']."' />";
      echo "<input type='submit' name='YES' value='YES' />";
      echo "&nbsp;&nbsp;<input type='submit' name='NO' value='NO' />";
      echo "</form>";
      echo "<br /><a href='".$back."' >Back</a>";
    } else
      if( isset($_REQUEST['YES']) ) {
      deleteUser( $_REQUEST['email'] );
      echo "<h2>This ".$_REQUEST['email']." is GONE!</h2>";
      echo "<br /><a href='".$back."' >Back</a>";
    } else
     if( isset($_REQUEST['Update']) ) {
// TODO - Add checks for ommitted fields!
       $status = updateUser( $_REQUEST['email'], $_REQUEST['engagemoreid'],
          $_REQUEST['orderid'], $_REQUEST['invoiceid'],
          $_REQUEST['product'], $_REQUEST['accountType'], $_REQUEST['status']);
       echo "<h2>Account ".$_REQUEST['email']." $status!</h2>";
       echo "<br /><a href='".$back."' >Back</a>";
     } else
     if( isset($_REQUEST['email']) ) {
       $user = getUserByEmail( $_REQUEST['email'] );
       if( $user ) {
         showUserForm( $user, $back );
       } else {
         echo "User ".$_REQUEST['email']." does not exist in users table";
         echo "<br /><br /><a href='".$back."' >Back</a>";
       }
     } else
     if( isset($_REQUEST['new']) ) {
       showUserForm( "", $back );
     }
      else {
?>
      <table id='tests'>
        <thead>
          <tr><th>#</th><th>ID</th><th>Email</th><th>EngagemoreID</th><th>Order</th><th>Invoice</th><th>Product</th><th>Status</th><th>Type</th><th>Since</th></tr>
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
      "</td><td>".$user['accountType'].
      "</td><td>".$user['added'].
      "</td></tr>";
}


echo "</tbody></table>";
  } // end of showing users
}
echo "<br /><a href='".$back."' >Back</a>";

echo "</center><footer>";
include '../webhook/git-info.php';
echo "</footer></div></div></div>";

?>
</body>
</html>
