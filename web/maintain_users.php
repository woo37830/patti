<?php
session_start(); //don't forget to do this
$location = "/patti/web/maintain_users.php";

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
            oTable = $('#users').DataTable({
            processing: true,
            bStateSave: true,
            ajax: {
                url: "./get_users.php",
                dataSrc: "data"
            },
            columns: [
                { data: "id", width: "5%" },
                { data:  "email" , width: "25%" },
                { data:  "engagemoreid" },
                { data:  "orderid" },
                { data: "product" },
								{ data: "status" },
								{ data: "accountType" },
								{ data: "added" }
            ]
            });

            /*setInterval( function() {
                oTable.ajax.reload(null, false);
            }, 30000 );*/

            $(document).on('click','#users tbody tr',function() {
                var row = $(this).closest("tr");
                editUser($(row).find("td:nth-child(1)").text(),$(row).find("td:nth-child(2)").text(),$(row).find("td:nth-child(3)").text(),$(row).find("td:nth-child(4)").text(), $(row).find("td:nth-child(5)").text(), $(row).find("td:nth-child(6)").text(),  $(row).find("td:nth-child(7)").text(),  $(row).find("td:nth-child(8)").text());
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
<body>
    <div class="wrapper">
	    <div class="content">
            <div id="page" >
                <div class="title">EngagemoreCRM Users</div>
                <hr/>
                <table id="users" class="tablesorter">
                    <thead>
                        <tr>
                            <th class="id_name">ID</th>
                            <th class="author_name">Email</th>
                            <th class="title_name">CRM</th>
                            <th class="genre_name">Order</th>
                            <th class="product_name">Product</th>
														<th class="status_name">Status</th>
														<th class="type_name">Type</th>
														<th class="since_name">Since</th>
                        </tr>
                 </table>
                <div id="toolbar" >
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a>
                </div>
	    </div>
	</div>
	</div>
    <footer class="footer" id="footer-div" /footer>
  </div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">User</div>
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<label for="email">Email:</label>
				<input name="email" class="easyui-textbox" required="true">
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
	<script type="text/javascript">
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
					alert('success achieved');
			//		var result = eval('('+result+')');
			//		if (result.errorMsg){
			//			$.messager.show({
			//				title: 'Error',
			//				msg: result.errorMsg
			//			});
			//		} else {
						$('#dlg').dialog('close');		// close the dialog
						//$("#users").dataTable()._fnAjaxUpdate();
            oTable.ajax.reload(null, false);
      //              }
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
        var logged_in = isset($_SESSION['loggedIn'])
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
