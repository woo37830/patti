<?php
session_start(); //don't forget to do this
$location = "/patti/web/maintain_accounts.php";

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
     <script type="text/javascript" class="init">
        var oTable;
        var json;
        $(document).ready(function() {
            oTable = $('#users').DataTable({
            processing: true,
            bStateSave: true,
            ajax: {
                url: "./ajaxAccounts.php",
								dataType: "JSON",
                dataSrc: "accounts.account"
            },
            columns: [
                { data: "accountid", width: "5%" , title: "ID"},
                { data:  "email" , width: "25%", title: "Email" },
								{ data: "mailmerge_fullname",
									"defaultContent": "", title: "Name"},
                { data:  "group_name" , title: "Product"},
								{ data: "account_status", title: "Status" },
								{ data: "license_type", width: "10%", title: "Type" },
								{ data: "create_date", title: "Added" }
            ]
            });

            /*setInterval( function() {
                oTable.ajax.reload(null, false);
            }, 30000 );*/

						$(document).on('click','#users tbody tr',function() {
								var row = $(this).closest("tr");
								editAccount($(row).find("td:nth-child(1)").text(),$(row).find("td:nth-child(2)").text(),$(row).find("td:nth-child(3)").text(),$(row).find("td:nth-child(4)").text(), $(row).find("td:nth-child(5)").text(), $(row).find("td:nth-child(6)").text(),  $(row).find("td:nth-child(7)").text());

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
</head>
<body
    <div class="wrapper">
	    <div class="content">
            <div id="page" >
                <div class="title">EngagemoreCRM Accounts</div>
								<div id="messages">
									<div id="message"></div>
									<div id="error_div"></div>
								</div>
                <hr/>
								<div id='info-div'>This page provides a list of all CRM accounts.</div>
	              <table id="users" class="tablesorter"></table>
      <!-- div id="toolbar" >
          <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newAccount()">New Account</a>
      </div -->
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
		<div class="ftitle">Account</div>
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<label for="email">Email:</label>
				<input name="email" class="easyui-textbox" required="true">
			</div>
			<div class="fitem">
				<label for="mailmerge_fullname">Name:</label>
				<input name="mailmerge_fullname" class="easyui-textbox">
			</div>
 			<div class="fitem">
				<label for="group_name">Product:</label>
				<input name="group_name" class="easyui-textbox">
			</div>
			<div class="fitem">
				<label for="account_status">Status:</label>
				<input name="account_status" class="easyui-textbox">
			</div>
			<div class="fitem">
				<label for="license">License:</label>
				<input name="license" class="easyui-textbox">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
	    <div id="sql_buttons" class="show-sql">
		<!-- a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-destoy" onclick="destroyAccount()" style="width:90px">Remove</a -->
	   </div>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<script type="text/javascript">
		var url;
		var row;
		function newAccount(){
			$('#dlg').dialog('open').dialog('setTitle','New Account');
			$('#fm').form('clear');
			url = './save_account.php';
		}
		function editAccount( id, email, name, product, status, accountType, added){
			if (email){
				$('#dlg').dialog('open').dialog('setTitle','Edit Account');
				$('#fm').form('load',{
				    email: email,
				    mailmerge_fullname: name,
				    group_name: product,
				    account_status: status,
						license: accountType
				});
				row = id;
				url = './update_account.php?id='+id;
			}
		}
		function saveAccount(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
			//		var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						$('#dlg').dialog('close');		// close the dialog
						//$("#users").dataTable()._fnAjaxUpdate();
            oTable.ajax.reload(null, false);
          }
				}
			});
		}
		function destroyAccoount(){
                var id = row;
			if (id){
				$.messager.confirm('Confirm','Are you sure you want to destroy this account?',function(r){
					if (r){
						$.post('./destroy_account.php',{id:id},function(result){
							if (result.success){
									alert('Success!');
						      $('#dlg').dialog('close');		// close the dialog
						        //$("#users").dataTable()._fnAjaxUpdate();
                  oTable.ajax.reload(null, false);
              } else {
								alert('Failure');
						//		$.messager.show({	// show error message
						//			title: 'Error',
						//			msg: result.errorMsg
						//		});
							}
						},'json');
					}
				});
			}
		}
	// Cookie functions
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
	//alert("Cookie: "+name+": "+readCookie(name));
}
var logged_in = true;
if ( logged_in ) {
		sql_buttons.className = 'show';
} else {
		sql_buttons.className = 'hide';
}
</script>
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
</body>
</html>
