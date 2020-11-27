<?php
session_start(); //don't forget to do this
$location = "/patti/web/MonitorLogs.php";

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
	<script type="text/javascript" src="./_js/cookies.js"></script>
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
            oTable = $('#logs').DataTable({
            processing: true,
            bStateSave: true,
            ajax: {
                url: "./get_logs.php",
                dataSrc: "data"
            },
            columns: [
                { data: "id", width: "5%", title: "ID" },
								{ data: "received", title: "Received" },
                { data: "email" , width: "25%", title: "Email" },
                { data: "request_json" , title: "Request" },
                { data: "commit_hash" , title: "Commit" },
                { data: "branch", title: "Branch" },
								{ data: "status", title: "Status" }
            ]
            });
						$("#info-img").click(function() {
								var $messageDiv = $('#info-div'); // get the reference of the div
								$messageDiv.slideDown(function() {
										$messageDiv.css("visibility", "visible"); // show and set the message
										setTimeout(function() {
												$messageDiv.slideUp();
										}, 20000);
								});
							});
            /*setInterval( function() {
                oTable.ajax.reload(null, false);
            }, 30000 );*/

            $(document).on('click','#logs tbody tr',function() {
                var row = $(this).closest("tr");
                viewLog($(row).find("td:nth-child(1)").text(),$(row).find("td:nth-child(2)").text(),$(row).find("td:nth-child(3)").text(),$(row).find("td:nth-child(4)").text(), $(row).find("td:nth-child(5)").text(), $(row).find("td:nth-child(6)").text(),  $(row).find("td:nth-child(7)").text());
            });
        });
				$.ajax({
					url: "./git-info.php",
					dataType: "text",
					success: function(data) {
						$('#footer-div').append(data);
				}
			});
			function goBack()
			{
				window.location = parameters.get('back');
			}
			var parameters = new URLSearchParams(window.location.search);
		</script>
</head>
<body>
    <div class="wrapper">
	    <div class="content">
            <div id="page" >
                <div class="title">Monitor Server Logs</div>
                <hr/>
								<div id='info-img'></div>
								<div id='back'>
									<a href="javascript:void(0)" class="easyui-linkbutton" [plain]="true" iconCls="icon-back" onclick="goBack()" style="width:90px">Back</a>
								</div>
								<div id='info-div'>This page allows the user to examine the database logs and look at the request data via the json-data.log file.</div>
								<table id="logs" class="tablesorter" width="95%"></table>
  	    		</div> <!-- end of page -->
			</div> <!-- end of content -->
			<hr />
			<div class="footer" id="footer-div"> </div>
	</div> <!-- end of wrapper -->

	<script type="text/javascript">
		var url;
		var row;
		function newUser(){
			$('#dlg').dialog('open').dialog('setTitle','New User');
			$('#fm').form('clear');
			url = './save_user.php';
		}
		function viewLog( id, added, email, engagemoreid, orderid, product, status){
			alert('View the selected log with id: '+id+' for email: '+email+'. Use php to grep log entry from file with that id');

		}
</script>

</body>
</html>
