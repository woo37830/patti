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
    var dataSet = [
    [ "<a href=\"../index.html\">Documentation</a>",
      "System Architect", "Update"],
    ["<a href=\"../tests/monthly_report.php\">Monthly Report</a>",
      "Get a monthly report","Move to web dir and update Look and Feel"],
    ["<a href=\"./getAccountId.php\">Get Account Id</a>",
      "Get the account id for an email",""],
    ["<a href=\"./maintain_accounts.php\">Maintain Accounts</a>",
      "Maintain Engagemore Accounts",""],
    ["<a href=\"./maintain_users.php\">Maintain Users</a>","Maintain User Database table",""],
    ["<a href=\"./Consistency.php\">Consistency Report</a>","Find insconsistencies","Fix add user"]
    ];

     var oTable;
     var json;
     $(document).ready(function() {
       $('#index').DataTable( {
        data: dataSet,
        columns: [
            { title: "Application" },
            { title: "Purpose" },
            { title: "Comment" }
        ]
    } );
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
    <table id="index" class="tablesorter" width="95%"></table>
  </center>
</div> <!-- end of page -->
</div> <!-- end of content div -->
<hr />
<div id="footer-div" class="footer"></div>
</div> <!-- end of wrapper -->

</body>
</html>
