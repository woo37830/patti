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
  padding: 5px 5px 5px 5px;
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
  height: 95vh;
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
</style>
</head>
<body>
<div id='page'>
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
  <tr><td>Add Contact</td><td>Add a contact to an account</td><td>Add authentication</td></tr>
  <tr><td>Add Contact Note</td><td>Create a note and add it to a contact</td><td>Add authentication</td></tr>
  <tr><td>GetContact</td><td>Get a contact using their email</td><td></td></tr>
  <tr><td>Get Contacts</td><td>Get the list of contacts for an account</td><td></td></tr>
  <tr><td>Add Account</td><td>Add account information to users_db.users table</td><td>Add authentication</td></tr>
  <tr><td>Update Account</td><td>Update account information in users_db.users table</td><td>Add authentication</td></tr>
  </tbody>
  </table>
  </center>
</div> <!-- end of content div -->
<footer>
EOS;
  return $html;
}

echo getHTML();
include '../webhook/git-info.php';
echo "</footer></div></body></html>";
?>
