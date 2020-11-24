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
  table.tablesorter
	{ /* So it won't keep expanding */
      table-layout: fixed
  }
</style>
</head>
<body>
	 <center>
  <h1>EngagemoreCRM Consistency Report</h1>
	<div id='wrapper'>
	<div id='content'>
  <div id='page'>
      <br />
<script type="text/javascript">
var oTable;
var json;
var k = 0;
	$(document).ready(function() {

			oTable = $('#accounts').DataTable({
			processing: true,
			bStateSave: true,
			ajax: {
					url: "./ajaxIsolatedAccounts.php",
					dataType: "json",
					dataSrc: "",
			},
			columns: [
					{ data: "accountid", width: "5%", title: "ID" },
					{ data:  "email" , width: "25%", title: "Email" },
					{ data: "mailmerge_fullname", title: "Name",
						"defaultContent": "" },
					{ data:  "group_name", title: "Product" },
					{ data: "account_status", title: "Status" },
					{ data: "license_type", width: "10%", title: "Type" },
					{ data: "create_date", title: "Since" }
			]
			});

			pTable = $('#users').DataTable({
			processing: true,
			bStateSave: true,
			ajax: {
					url: "./ajaxIsolatedUsers.php",
					dataType: "json",
					dataSrc: "data",
			},
			columns: [
					{ data: "id", width: "5%", title: "ID" },
					{ data: "email" , width: "25%" , title: "Email"},
					{ data: "engagemoreid",  width: "10%", title: "Account" },
					{ data: "orderid", title: "Order ID" },
					{ data: "product", title: "Product" },
					{ data: "status",  width: "10%", title: "Status" },
					{ data: "accountType", width: "10%", title: "Type" },
					{ data: "added", title: "Since" }
			]
			});

});
$.ajax({
	url: "./git-info.php",
	dataType: "text",
	success: function(data) {
		$('#footer-div').append(data);
}
});
</script>
<style type="text/css">
	.datatable td {
						overflow: hidden; /* this is what fixes the expansion */
						text-overflow: ellipsis; /* not supported in all browsers, but I accepted the tradeoff */
						white-space: nowrap;
			}
			table.tablesorter { /* So it won't keep expanding */
					table-layout: fixed
			}
</style>
<div class="easyui-tabs" style="width:90%;height:80%">
		<div title="Accounts" style="padding:10px" class="tab">

<h1>Isolated Accounts</h1>
<hr />
<br />
<table id="accounts" class="tablesorter" width="95%"></table>
</div>
<div title="Users" class="tablesorter" width="95%" class="tab">

<h1>Isolated Users</h1>
<hr />
<br />
<table id="users" class="tablesorter" width="95%"></table>
</div>
</div>

</center>
</div> <!-- end of page -->
</div> <!-- end of content -->
<hr />
<div class="footer" id="footer-div"> </div>
</div> <!-- end of wrapper -->
</body>
</html>
