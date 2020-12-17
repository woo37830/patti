<?php
session_start(); //don't forget to do this
$location = "/patti/web/Template.php";

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
	<LINK REL="stylesheet" HREF="_css/hamburger.css" id="styleid"/>
	<script type="text/javascript" class="init">
    var dataSet = [
    ["<a href=\"../web/testNewAccount.php?back=./Tests.php\">New Account</a>",
      "Create new Account","Simulate Thrivecart New Account"],
    ["<a href=\"./testCancelAccount.php?back=./Tests.php\">Cancel Account</a>",
      "Cancel an Account","Simulate Thrivecart Cancel Account"],
		["<a href=\"./testResumeAccount.php?back=./Tests.php\">Resume Account</a>",
	      "Resume a Suspended Account","Simulate Thrivecart order.subscription_resume"],
		["<a href=\"./testAddContactNote.php?back=./Tests.php\">Add Contact Note</a>",
	      "Add a Contact Note","Simulate adding a contact note"],
		["<a href=\"./testCartAbandoned.php?back=./Tests.php\">Abandon Cart</a>",
			   "Abandon cart while shopping","Simulate a Cart.Abandoned"],
    ["<a href=\"./testUpgradeAccount.php?back=./Tests.php\">Upgrade Account</a>",
      "Upgrade an Account","Simulate Thrivecart Upgrade Account"],
		["<a href=\"./TestGroupName.html?back=./Tests.php\">Test Group Name from Product-Id</a>",
				"Test Group Name","Test Getting Group Name from product-id"],
    ["<a href=\"./testMissPayment.php?back=./Tests.php\">Credit Card Rejected</a>","Credit Card Doesn't Renew","Simulate Thrivecart Rebill Failed"],
    ];

     var oTable;
     var json;
		 var parameters;
     $(document).ready(function() {
       $('#tests').DataTable( {
        data: dataSet,
        columns: [
            { title: "Test" },
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
	    <div class="content">
            <div id="page" >
							<center>
						    <br />
						    <h1>Tests</h1>
						    <hr />
						    <br />
						    <table id="tests" class="tablesorter" width="95%"></table>
						  </center>
	    		</div> <!-- end of page -->
			</div> <!-- end of content -->
			<hr />
			<div class="footer" id="footer-div"> </div>
	</div> <!-- end of wrapper -->
	<div id='logoutDiv' style='display:block' align='right'>
	   <form id='logoutForm' type='POST' action="./index.php?<?=$action ?>" >
	     <input type='submit' id='sub_btn' name='submit' value='Logout'  />
	   </form>
	 </div>
	<div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">User</div>
		<form id="fm" method="post" novalidate>
			<div class="fitem">
			<label for="email">Email:</label>
			<input class="easyui-textbox" name="email" required="true">
			</div>
			<div class="fitem">
				<label for="engagemoreid">AccountID:</label>
				<input name="engagemoreid" class="easyui-textbox" required="true">
			</div>
			<div class="fitem">
				<label for="orderid">Order:</label>
				<input name="orderid" class="easyui-textbox">
			</div>
			<div class="fitem">
				<label for="product">Product:</label>
				<input name="product" class="easyui-textbox">
			</div>
			<div class="fitem">
				<label for="status">Status:</label>
				<input name="status" class="easyui-textbox">
			</div>
			<div class="fitem">
				<label for="accountType">Type:</label>
				<input name="accountType" class="easyui-textbox">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
	    <div id="sql_buttons" class="show-sql">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-destoy" onclick="destroyUser()" style="width:90px">Remove</a>
	   </div>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<script type="text/javascript>
		var url;
		var row;
		function newUser(){
			$('#dlg').dialog('open').dialog('setTitle','New User');
			$('#fm').form('clear');
			url = './save_user.php';
		}
		function editUser( id, email, engagemoreid, orderid, product, status, accountType, added){
			if (email){
				$('#dlg').dialog('open').dialog('setTitle','Edit User');
				$('#fm').form('load',{
				    email: email,
				    engagemoreid: engagemoreid,
				    orderid: orderid,
				    product: product,
				    status: status,
						accountType: accountType
				});
				row = id;
				url = './update_user.php?id='+id;
			}
		}
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
						alert('Success: ' + JSON.stringify(result));
						$('#dlg').dialog('close');		// close the dialog
						//$("#users").dataTable()._fnAjaxUpdate();
            oTable.ajax.reload(null, false);
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
