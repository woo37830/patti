<?php
session_start(); //don't forget to do this
$location = "/patti/web/testNewAccount.php";

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
                <div class="title">Resume Suspended Account</div>
                <hr/>
				<div id='info-img'></div>
				<div id='back'>
					<a href="javascript:void(0)" class="easyui-linkbutton" [plain]="true" iconCls="icon-back" onclick="goBack()" style="width:90px">Back</a>
				</div>
				<div id='info-div'>This Test Simulates a user informing Thrivecart to resume their suspended account.</div>
	    		</div> <!-- end of page -->
			</div> <!-- end of content -->
			<hr />
			<div class="footer" id="footer-div"> </div>
	</div> <!-- end of wrapper -->


	<div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
			closed="false" buttons="#dlg-buttons">
		<div class="ftitle">Account Information</div>
		<form id="fm" method="post" novalidate>
			<input type='hidden' name="thrivecart_secret" value="IEYDASLZ8FR7" />
			<input type='hidden' name="event" value='order.subscription_resumed' />
			<input type='hidden' name="invoice_id" value="123" />
			<div class="fitem">
			<label for="email">Email:</label>
			<input class="easyui-textbox" name="customer[email]" required="true">
			</div>
			<div class="fitem">
				<label for="product">Base Product:</label>
				<input name="base_product" class="easyui-textbox">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
	    <div id="sql_buttons" class="show-sql">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
			</div>
	</div>
	<script type="text/javascript">
		var url = '../webhook/thrivecart.php';
		function saveUser(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
				//	var result = eval('('+result+')');
					if (result.errorMsg){
						alert('Error: ' + JSON.stringify(result));
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(JSON.stringify(result));
						$('#dlg').dialog('close');		// close the dialog
						//$("#users").dataTable()._fnAjaxUpdate();
            //oTable.ajax.reload(null, false);
                    }
				}
			});
		}
		function destroyUser(){
      var id = row;
			if (id){
				$.messager.confirm('Confirm','Are you sure you want to destroy this user?',function(r){
					if (r){
						$.post('./destroy_user.php',{id:id},function(result){
							if (result.success){
						      $('#dlg').dialog('close');		// close the dialog
						        //$("#users").dataTable()._fnAjaxUpdate();
                  oTable.ajax.reload(null, false);
              } else {
								alert('Failure');
								$.messager.show({	// show error message
									title: 'Error',
									msg: result.errorMsg
								});
							}
						},'json');
					}
				});
			}
		}
		var logged_in = true;
		if ( logged_in ) {
				sql_buttons.className = 'show';
		} else {
				sql_buttons.className = 'hide';
		}

</script>

</body>
</html>
