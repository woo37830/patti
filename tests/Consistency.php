<html>
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


table tr th {
  border: 1px solid #000;
  padding: 10px;
}
table tr td {
  border: 1px solid #000;
  padding: 10px;
}



/* Zebra stripe */
table tr:nth-child(odd) {
  background: #f4f4f4;
}

</style>
</head>
<body>
  <div id='page'>
    <div id='content'>
<?php
// Test the addContactNote


//require_ '../webhook/mysql_common.php';
require '../webhook/get_all_accounts.php';

date_default_timezone_set("America/New_York");

$today = date("D M j G:i:s T Y");
function showData( $user ) {
  echo "<tr><td><a href=\"http://ec2-44-241-140-144.us-west-2.compute.amazonaws.com/patti/GetAllUsers.php?back=Consistency.php\">".$user['id']."</a>".
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
  echo "<tr><td> $account->accountid</td><td> $account->email</td><td>$account->mailmerge_fullname</td><td>$account->group_name</td><td>$account->license_type</td><td>$account->account_status</td><td>$account->create_date</td></tr>";
}
function getAUser( $email, $users ) {
  foreach($users as $user) {
    if( $user['email'] == $email ) {
      return $user;
    }
  }
  return -1;
}
function getAccount( $email, $accounts ) {
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
echo "<table id='accounts'>" .
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

echo "<table id='tests'>".
  "<thead>".
  "<tr><th>ID</th><th>Email</th><th>EngagemoreID</th><th>Order</th><th>Invoice</th><th>Product</th><th>Status</th><th>Since</th></tr>".
  "</thead>".
  "<tbody>";
$usersfile = fopen("/tmp/Users.csv", "w");
fwrite($usersfile, "ID,Email,EngagemoreID,Order,Invoice,Product,Status,Since\n"); // Write Header
foreach($users as $user){
//    print_r($user);
  if( getAccount( $user['email'], $accounts ) == -1 ) {
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
