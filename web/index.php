<?php
session_start(); //don't forget to do this
$location = "/patti/web/index.php";

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
  <script type="text/javascript" class="init">
     var oTable;
     var json;
     $(document).ready(function() {
       $.ajax({
      url: "./git-info.php",
      dataType: "text",
      success: function(data) {
        $('#footer-div').append(data);
    }
  });
});
</script>
</head>
<body>
<div class="wrapper">
<div class='content'>
  <div id='page'>
  <center>
    <br />
    <h1>Applications</h1>
    <hr />
    <br />
  <table id="users" class="tablesorter">
  <thead>
  <tr><th>Application</th><th>Purpose</th><th>Comment</th></tr>
  </thead>
  <tbody>
  <tr><td><a href="../index.html">Documentation</a></td><td>Documents describing applications</td><td>Update theese</th></tr>
  <tr><td><a href="./monthly_report.php">Monthly Report</a></td><td>Get a monthly report</td><td>Add input for month</th></tr>
  <tr><td><a href="../billing.php">Billing Report</a></td><td>Get a report on Billing</td><td>Add input for month</th></tr>
  <tr><td><a href="./getAccountId.php">Get Account Id</a></td><td>Get the account id for an email</td><td></td></tr>
  <tr><td><a href="./maintain_accounts.php">Maintain Accounts</a></td><td>Maintain Engagemore Accounts</td><td></td></tr>
  <tr><td><a href="./maintain_users.php">Maintain Users</a></td><td>List, Update, delete users_db.users</td><td>Add DataTables and nav at top and new user</td></tr>
  <tr><td><a href="./Consistency.php">Consistency Report</a></td><td>Check for extra or missing account information,csv files to tmp</td><td>Add check for order id, etc., Add links to Update User</td></tr>
  <tr><td><a href="./testAddContact.php">Add Contact</a></td><td>Add a contact to an account</td><td>Style info?</td></tr>
  <tr><td>Add Contact Note</td><td>Create a note and add it to a contact</td><td>Working with login</td></tr>
  <tr><td>GetContact</td><td>Get a contact using their email</td><td>Add login</td></tr>
  <tr><td>Get Contacts</td><td>Get the list of contacts for an account</td><td>Add login and links to Add Contact Note</td></tr>
  </tbody>
  </table>
  </center>
</div> <!-- end of page -->
</div> <!-- end of content div -->
</div>
<footer class="footer" id="footer-div" /footer>
</div>
</body>
</html>
