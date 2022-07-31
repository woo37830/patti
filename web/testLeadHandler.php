<?php
session_start(); //don't forget to do this
$location = "/patti/web/testLeadHandler.php";

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
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/color.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/demo/demo.css">
	<LINK REL="stylesheet" HREF="_css/home.css" id="styleid"/>
   <script type="text/javascript" class="init">
        var oTable;
        var json;
				$(document).ready(function() {
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
                <div class="title">Test Lead Handler</div>
                <hr/>
								<div id='info-img'></div>
								<div id='back'>
									<a href="javascript:void(0)" class="easyui-linkbutton" [plain]="true" iconCls="icon-back" onclick="goBack()" style="width:90px">Back</a>
								</div>
								<div id='info-div'>This Test simulates a lead provider sending a lead to an account holder.</div>
	    		</div> <!-- end of page -->
			</div> <!-- end of content -->
			<hr />
			<div class="footer" id="footer-div"> </div>
	</div> <!-- end of wrapper -->

	<div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
			closed="false" buttons="#dlg-buttons">
		<div class="ftitle">Account</div>
		<form id="email" name="email" method="post" novalidate>
			<input type="hidden" name="headers" value="from example@example.com  sat nov 20 22"/>
			<input type="hidden" name="return-path" value="<example@example.com>/>

			<div class="fitem">
			<label for="message">Message:</label>
			<input class="easyui-textbox" name="message" required="true" style="width:380px;padding:10px 20px">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
	    <div id="sql_buttons" class="show-sql">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-destoy" onclick="send()" style="width:90px">Send</a>
	   </div>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<script type="text/javascript">
		var url;
		var row;
		var url = '../email-extractor/lead-handler.php';
		function send(){
			$('#email').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
				//	var result = eval('('+result+')');
					if (result.errorMsg){
						alert('Error: ' + result.errorMsg + ', ' + JSON.stringify(result));
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(JSON.stringify(result));
						$('#dlg').dialog('close');		// close the dialog
                    }
				}
			});
		}

</script>

</body>
</html>
