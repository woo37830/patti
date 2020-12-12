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
    ["<a href=\"./maintain_accounts.php\">Maintain Accounts</a>",
      "Maintain Engagemore Accounts",""],
    ["<a href=\"./maintain_users.php\">Maintain Users</a>","Maintain User Database table",""],
    ["<a href=\"./Consistency.php\">Consistency Report</a>","Find insconsistencies","Fix add user"],
		["<a href=\"./Tests.php\">Tests</a>","Run Webhook Tests","Simulate Thrivecart Actions"],
		["<a href=\"./MonitorLogs.php\">Monitor Logs</a>","Monitor Server Logs", "Clean this up"]
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
<div id='logoutDiv' style='display:block' align='right'>
   <form id='logoutForm' type='POST' action="./index.php?<?=$action ?>" >
     <input type='submit' id='sub_btn' name='submit' value='Logout'  />
   </form>
 </div>
 <div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
   <div class="ftitle">Preferences</div>
   <form id="fm" method="post" novalidate>
     <div class="fitem">
       <label for="user">User:</label>
       <input name="user" class="easyui-textbox" required="true">
     </div>
     <div class="fitem">
       <label for="skin">Skin:</label>
       <input name="skin" class="easyui-textbox" required="true">
     </div>
     <div class="fitem">
       <label for="logo">Logo:</label>
       <input name="logo" class="easyui-textbox">
     </div>
   </form>
 </div>
 <div id="dlg-buttons">
     <div id="sql_buttons" class="show-sql">
   <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePrefs()" style="width:90px">Save</a>
   <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
 </div>

</body>
</html>
