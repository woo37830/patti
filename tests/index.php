<?php
function getHTML() {
$html = <<<EOS
<html>
<head>
<title>Tests</title>
<style type="text/css">
table {
  border-collapse: collapse;
}

table, th, td {
  width: 90vw;
  border: 1px solid black;
  padding: 2px 2px 2px 2px;
}
#content {
  width: 100vw;
  height: 100vh;
}
</style>
</head>
<body>
<div id='content'>
<center>
  <br />
  <h1>Applications</h1>
  <hr />
  <br />
<table>
<thead>
<tr><th>Application</th><th>Purpose</th><th>Comment</th></tr>
</thead>
<tbody>
<tr><td><a href="../webhook/monthly_report.php">Monthly Report</a></td><td>Get a monthly report</td><td>Add input for month</th></tr>
<tr><td><a href="./getAccountId.php">Get Account Id</a></td><td>Get the account id for an email</td><td>Result xml seems bad</td></tr>
<tr><td>Add Contact</td><td>Add a contact to an account</td><td></td></tr>
<tr><td>Add Contact Note</td><td>Create a note and add it to a contact</td><td></td></tr>
<tr><td>GetContact</td><td>Get a contact using their email</td><td></td></tr>
<tr><td>Get Contacts</td><td>Get the list of contacts for an account</td><td></td></tr>
<tr><td>Add Account</td><td>Add account information to users_db.users table</td><td></td></tr>
<tr><td>Update Account</td><td>Update account informatin in users_db.users table</td><td></td></tr>
</tbody>
</table>
</center>
<div id='footer' ><hr />
  <em>
EOS;
  return $html;
}

echo getHTML();
include '../webhook/git-info.php';
echo "</em></div></div></body></html>";
?>
