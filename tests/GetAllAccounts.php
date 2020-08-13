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
  <div id='page'>
    <div id='content'>
      <table id='tests'>
        <thead>
          <tr><th>#</th><th>ID</th><th>Email</th><th>Full Name</th><th>Status</th><th>Since</th></tr>
        </thead>
        <tbody>
<?php
// Test the addContactNote


require '../webhook/get_all_accounts.php';

$today = date("D M j G:i:s T Y");
$accounts = getAllAccounts();
$k = 1;
foreach($accounts as $account){
  echo "<tr><td>" . $k++ . "</td><td> $account->accountid</td><td> $account->email</td><td>$account->mailmerge_fullname</td><td>$account->account_status</td><td>$account->create_date</td></tr>";
}


echo "</tbody></table>";
echo "<footer>";
include '../webhook/git-info.php';
echo "</footer></div></div></div></body></html>";

?>
</body>
</html>
