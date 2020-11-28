<?php
session_start(); //don't forget to do this
$location = "/patti/tests/GetAllAccounts.php";

require('fancyAuthentication.php');

?>
<html>
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
#tests th {
  background: black;
  color: white;
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
  <center>
  <br />
  <h1>EngagemoreCRM Accounts</h1>
  <div id='page'>
    <div id='content'>
      <table id='tests'>
        <thead>
          <tr><th>#</th><th>ID</th><th>Email</th><th>Full Name</th><th>Group Name</th><th>License Type</th></th><th>Status</th><th>Since</th></tr>
        </thead>
        <tbody>
<?php
// Test the addContactNote


require '../webhook/get_all_accounts.php';

$today = date("D M j G:i:s T Y");
$back = './GetAllAccounts.php';
$users = './GetAllUsers.php?back='.$back.'&email=';
$accounts = getAllAccounts();
$k = 1;
foreach($accounts as $account){
  echo "<tr><td>" . $k++ . "</td><td>$account->accountid</td><td><a href='".$users.$account->email."' >$account->email</a></td><td>$account->mailmerge_fullname</td><td>$account->group_name</td><td>$account->license_type</td><td>$account->account_status</td><td>$account->create_date</td></tr>";
}


echo "</tbody></table>";
echo "<footer>";
include '../webhook/git-info.php';
echo "</footer></div></div></div></body></html>";

?>
</body>
</html>
