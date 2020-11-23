<?php
session_start(); //don't forget to do this
$location = "/patti/web/Consistency.php";

require('fancyAuthentication.php');

?>
<html>
<!-- $Author: woo $   -->
<!-- $Date: 2017/11/14 16:37:22 $     -->
<!-- $Revision: 1.5 $ -->
<!-- $Source: /Users/woo/cvsrep/library/index.html,v $   -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset="UTF-8">
	<title>EngagemoreCRM</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="http://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"/>
    <LINK REL="stylesheet" HREF="_css/jquery.dataTables_themeroller.css" />
    <!-- link rel="stylesheet" href="_css/jquery.tablesorter.pager.css" / -->
	<LINK REL="stylesheet" HREF="_css/home.css" id="styleid"/>
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/color.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/demo/demo.css">

<style type="text/css">
  table.tablesorter { /* So it won't keep expanding */
      table-layout: fixed
  }
</style>
</head>
<body>
  <a href='./index.php'>Back</a>
    <center>
  <br />
  <h1>EngagemoreCRM Consistency Report</h1>
  <div id='page'>
    <div id='content'>
      <br />
        <div id='page'>
<?php
// Test the addContactNote


//require_ '../webhook/mysql_common.php';
require '../webhook/get_all_accounts.php';

date_default_timezone_set("America/New_York");

$today = date("D M j G:i:s T Y");
function showData( $user ) {
  echo "<tr><td><a href=\"./maintain_users.php?back=Consistency.php\">".$user['id']."</a>".
    "</td><td>".$user['email'].
    "</td><td>".$user['engagemoreid'].
    "</td><td>".$user['orderid'].
    "</td><td>".$user['invoiceid'].
    "</td><td>".$user['product'].
    "</td><td>".$user['status'].
    "</td><td>".$user['added'].
    "</td></tr>";
}
function showAccount($account ) {
  echo "<tr><td><a href=\"./maintain_accounts.php?back=Consistency.php\">$account->accountid</a></td><td> $account->email</td><td>$account->mailmerge_fullname</td><td>$account->group_name</td><td>$account->license_type</td><td>$account->account_status</td><td>$account->create_date</td></tr>";
}
function getAUser( $email, $users ) {
  foreach($users as $user) {
    if( $user['email'] == $email ) {
      return $user;
    }
  }
  return -1;
}
function getAccountById( $id, $accounts ) {
  foreach( $accounts as $account ) {
    if( $account->id == $id ) {
      return $account;
    }
  }
  return -1;
}
function getAccountByEmail( $email, $accounts ) {
  foreach( $accounts as $account ) {
    if( $account->email == $email ) {
      return $account;
    }
  }
  return -1;
}
$accounts = getAllAccounts();
$users = getAllUsers();
// Loop through accounts, see if there is a user for each account.
// Display accounts with no user.
// If account has user, check that user has engagemoreid that agrees
//      Check that accounts status is the same as the users status ?
//
echo "<hr /><h1>Accounts with no entry in user table</h1><hr /><br>";
echo "<table id='accounts' class='tablesorter'>" .
  "<thead>".
    "<tr><th>ID</th><th>Email</th><th>Full Name</th><th>Group Name</th><th>License Type</th><th>Status</th><th>Since</th></tr>".
  "</thead>".
  "<tbody>";
$acctsfile = fopen("/tmp/Accounts.csv", "w");
fwrite($acctsfile, "ID,Email,Full Name,Group Name, Account Type, Status,Since\n"); // Write Header
foreach($accounts as $account){
  if(  getAUser( $account->email, $users ) == -1 )
  {
    if( (strpos($account->email,"@") !== false) ) {
      showAccount( $account );
      fwrite($acctsfile,"$account->accountid,$account->email,\"$account->mailmerge_fullname\",\"$account->group_name\",\"$account->license_type\",$account->account_status,$account->create_date\n");
    }
  }
}
fclose($acctsfile);
echo "</tbody></table>";

// Loop through users, see if there is an account for each user
// Display users tht have not accounts.
// If user has account, check that user has engagemore id that agrees
//       Check that users status is the same as the account status ?
//
echo "<hr /><h1>Users with no entry in Engagemore</h1><hr /><br>";

echo "<table id='tests' class='tablesorter'>".
  "<thead>".
  "<tr><th>ID</th><th>Email</th><th>EngagemoreID</th><th>Order</th><th>Invoice</th><th>Product</th><th>Status</th><th>Since</th></tr>".
  "</thead>".
  "<tbody>";
$usersfile = fopen("/tmp/Users.csv", "w");
fwrite($usersfile, "ID,Email,EngagemoreID,Order,Invoice,Product,Status,Since\n"); // Write Header
foreach($users as $user){
//    print_r($user);
  if( getAccountById( $user['id'], $accounts ) == -1 ) {
    showData( $user );
  fwrite($usersfile,$user['id'].",".
          $user['email'].",".
          $user['engagemoreid'].",".
          $user['orderid'].",".
          $user['invoiceid'].",".
          $user['product'].",".
          $user['status'].",".
          $user['added']."\n");
  }
}
fclose($usersfile);

echo "</tbody></table>";

echo "<hr /><h1>Users with no engagemore ID</h1><hr /><br>";

echo "<table id='tests2'>".
  "<thead>".
  "<tr><th>ID</th><th>Email</th><th>EngagemoreID</th><th>Order</th><th>Invoice</th><th>Product</th><th>Status</th><th>Since</th></tr>".
  "</thead>".
  "<tbody>";

foreach($users as $user){
//    print_r($user);
  if( trim($user['engagemoreid']) == "") {
    showData( $user );
  }
}


echo "</tbody></table>";

echo "<footer>";
include '../webhook/git-info.php';
echo "</footer></div></div></div></body></html>";

?>
</body>
</html>
