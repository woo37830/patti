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
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/color.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/demo/demo.css">
	<LINK REL="stylesheet" HREF="_css/home.css" id="styleid"/>

</head>
<body>
	 <center>
  <h1>EngagemoreCRM Consistency Report</h1>
	<div id="messages">
		<div id="debug"></div>
		<div id="message"></div>
		<div id="error-div">
	 	</div> <!-- end of error-div -->
	</div>
	<div id="info-div">This report shows the accounts not in the users table and also the users that don't have accounts.</div>
	<div id='wrapper'>
	<div id='content'>
  <div id='page'>
<script type="text/javascript">
var groups = [];
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

			pTable = $('#emails').DataTable({
			processing: true,
			bStateSave: true,
			ajax: {
					url: "./ajaxChangedSignupEmail.php",
					dataType: "json",
					dataSrc: "data",
			},
			columns: [
					{ data: "engagemoreid",  width: "10%", title: "Account" },
					{ data: "ac_email" , width: "25%" , title: "Current"},
					{ data: "db_email" , width: "25%" , title: "Sign In"},
					{ data: "product", title: "Product" },
					{ data: "status",  width: "10%", title: "Status" },
					{ data: "added", title: "Since" }
			]
			});

});

$(document).on('click','#accounts tbody tr',function() {
		var row = $(this).closest("tr");
		addUser($(row).find("td:nth-child(1)").text(),$(row).find("td:nth-child(2)").text(),$(row).find("td:nth-child(3)").text(),$(row).find("td:nth-child(4)").text(), $(row).find("td:nth-child(5)").text(), $(row).find("td:nth-child(6)").text(),  $(row).find("td:nth-child(7)").text());
});

$(document).on('click','#users tbody tr',function() {
		var row = $(this).closest("tr");
		addUser($(row).find("td:nth-child(1)").text(),$(row).find("td:nth-child(2)").text(),$(row).find("td:nth-child(3)").text(),$(row).find("td:nth-child(4)").text(), $(row).find("td:nth-child(5)").text(), $(row).find("td:nth-child(6)").text(),  $(row).find("td:nth-child(7)").text());
});


$.ajax({
	url: "./git-info.php",
	dataType: "text",
	success: function(data) {
				$('#footer-div').append(data);
			}
});
$.ajax({
	url: "./ajaxProducts.php",
	dataType: "json",
	success: function(data) {
		groups = data;
	}
});
var url;
var row;
function newUser(){
	$('#dlg').dialog('open').dialog('setTitle','New User');
	$('#fm').form('clear');
	url = './save_user.php';
}
function addUser( engagemoreid, email, name, product, status, accountType, added){
	if (email){
		$('#dlg').dialog('open').dialog('setTitle','Add User');
		$('#fm').form('load',{
				engagemoreid: engagemoreid,
				email: email,
				product: groupNameToProduct( product),
				status: status,
				accountType: accountType
		});
		row = id;
		url = './save_user.php';
	}
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
				alert('Failure: ' + result.errorMsg);
				$.messager.show({
					title: 'Error',
					msg: result.errorMsg
				});
			} else {
				alert('Success!');
			  $('#dlg').dialog('close');		// close the dialog
				//$("#users").dataTable()._fnAjaxUpdate();
				pTable.ajax.reload(null, false);
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
							pTable.ajax.reload(null, false);
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


function groupNameToProduct( groupName ) {
//  alert('groupName: \"'+groupName+'\" groups: ' + JSON.stringify(groups));
  var done = 0;
  var result = 'unknown';
  $.each(groups, function(key, innerjson)
  {
    for( var key in innerjson )
    {
	//		alert( 'key: \"' + key + '\", product: ' + innerjson[key]);
      if( String(key) == String(groupName) )
      {
  //       alert('returning ' + innerjson[key]);
         result = innerjson[key];
         done = -1;
         break;
       }
    }
    if( done == -1 )
    {
      return false;
    }
  });
  return result;
}

</script>
<style type="text/css">
	<style type="text/css">
	        #log_in,#log_out {
	            float:  left;
	        }
	        #dlg-buttons div {
	            float: left;
	            clear: none;
	        }
	         #toolbar.hide {
	            display: none;
	        }
	        #toolbar.show {
	            display: block;
	        }
	        #sql-buttons.hide {
	            display: none;
	        }
	        #sql-buttons.show {
	            display: block;
	        }
		#fm{
			margin:0;
			padding:10px 30px;
		}
		.ftitle{
			font-size:14px;
			font-weight:bold;
			padding:5px 0;
			margin-bottom:10px;
			border-bottom:1px solid #ccc;
		}
		.fitem{
			margin-bottom:5px;
		}
		.fitem label{
			display:inline-block;
			width:80px;
		}
		.fitem input{
			width:160px;
		}
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

	<div title="Changed Emails" class="tablesorter" width="95%" class="tab">

		<h1>Accounts with Changed Emails from Signup</h1>
		<hr />
		<br />
		<table id="emails" class="tablesorter" width="95%"></table>
	</div>
</div>

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
 </div><div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons">
	<div class="ftitle">User</div>
	<form id="fm" method="post" novalidate>
		<div class="fitem">
			<label for="engagemoreid">ID:</label>
			<input name="engagemoreid" class="easyui-textbox" required="true">
		</div>
		<div class="fitem">
			<label for="email">Email:</label>
			<input name="email" class="easyui-textbox" required="true">
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
</body>
</html>
