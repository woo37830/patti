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
  <tr><td><a href="../index.html">Documentation</a></td><td>Documents describing applications</td><td>Update theese</th></tr>
  <tr><td><a href="./monthly_report.php">Monthly Report</a></td><td>Get a monthly report</td><td>Add input for month</th></tr>
  <tr><td><a href="../billing.php">Billing Report</a></td><td>Get a report on Billing</td><td>Add input for month</th></tr>
  <tr><td><a href="./getAccountId.php">Get Account Id</a></td><td>Get the account id for an email</td><td></td></tr>
  <tr><td><a href="./GetAllAccounts.php">Listing/Edit Accounts</a></td><td>Maintain Engagemore Accounts</td><td></td></tr>
  <tr><td><a href="./GetAllUsers.php">Maintain User Table</a></td><td>List, Update, delete users_db.users</td><td>Add DataTables and nav at top and new user</td></tr>
  <tr><td><a href="./Consistency.php">Consistency Report</a></td><td>Check for extra or missing account information,csv files to tmp</td><td>Add check for order id, etc., Add links to Update User</td></tr>
  <tr><td><a href="./testAddContact.php">Add Contact to an account</a></td><td>Add a contact to an account</td><td>Style info?</td></tr>
  <tr><td><a href="./testAddContactFromEmail.php">Add Contact Using Email</a></td><td>Add a contact to an account</td><td>Style info?</td></tr>
  <tr><td><a href="./testAddCancelContact.php">Add Contact as from Abandon Cart</a></td><td>Add a contact that abandoned cart to be examined by 2288</td><td>Style info?</td></tr>
  <tr><td><a href="./testAddContactNote.php">Add Contact Note</a></td><td>Create a note and add it to a contact</td><td>Working with login</td></tr>
  <tr><td>GetContact</td><td>Get a contact using their email</td><td>Add login</td></tr>
  <tr><td>Get Contacts</td><td>Get the list of contacts for an account</td><td>Add login and links to Add Contact Note</td></tr>
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
