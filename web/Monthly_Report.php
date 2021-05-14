<?php
session_start(); //don't forget to do this
$location = "/patti/web/Monthly_Report.php";

require('fancyAuthentication.php');
$months = array('','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
           'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$mon = (int)date("m");

if( isset($_POST['month']) ) {
   $mon = (int)$_POST['month'];
}
$year = (int)date('Y');
if( isset($_POST['year']) ) {
  $year = (int)$_POST['year'];
}
echo "<h1>Month: $mon, Year: $year</h1>"
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
				var pTable;
        var json;
      //Check is string null or empty
      function isStringNullOrEmpty(val) {
          switch (val) {
              case "":
              case 0:
              case "0":
              case null:
              case false:
              case undefined:
              case typeof this === 'undefined':
                  return true;
              default: return false;
          }
      };

      //Check is string null or whitespace
      function isStringNullOrWhiteSpace (val) {
          return this.isStringNullOrEmpty(val) || val.replace(/\s/g, "") === '';
      };

      //If string is null or empty then return Null or else original value
      function nullIfStringNullOrEmpty(val) {
          if (this.isStringNullOrEmpty(val)) {
              return null;
          }
          return val;
      };
      function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };
        $(document).ready(function()
				{
          var d = new Date();
          var _mon = document.getElementById('_mon').value;
          if( isStringNullOrEmpty(_mon) ) {
            _mon = d.getMonth();
          }
          document.getElementById('month_select').options[_mon].selected=true;

          var _year = document.getElementById('_year').value;
          if( isStringNullOrEmpty(_year) ) {
            _year = d.getYear();
          }
          alert("_year = '"+_year+"'");
          document.getElementById('year_select').options[_year].selected=true;

            oTable = $('#users').DataTable(
						{
	            processing: true,
	            bStateSave: true,
	            ajax:
							{
	                url: "./ajaxMonthlyUsers.php?month=$mon&year=$year",
	                dataSrc: "data",
	            },
	            columns: [
									{ data: "added", title: "Added" },
									{ data: "email" , width: "25%", title: "Email" },
									{ data: "engagemoreid" , title: "CRM" },
	  							{ data: "invoiceid" , title: "Invoice" },
									{ data: "product" , title: "Product" }
	              ]
            });

						pTable = $('#cancelled').DataTable(
						{
							processing: true,
							bStateSave: true,
							ajax:
							{
									url: "./ajaxMonthlyCancelled.php?month=$mon&year=$year",
									dataSrc: "data"
							},
							columns: [
              { data: "received" , title: "Cancelled" },
							{ data: "email" , title: "Email" },
              { data: "engagemoreid", title: "EngagemoreID"}
									]
						});

              $(document).on('click','#users tbody tr',function() {
                  var row = $(this).closest("tr");
                  editAccount($(row).find("td:nth-child(1)").text(),$(row).find("td:nth-child(2)").text(),$(row).find("td:nth-child(3)").text());
              });
              $(document).on('click','#cancelled tbody tr',function() {
                  var row = $(this).closest("tr");
                  editAccount($(row).find("td:nth-child(1)").text(),$(row).find("td:nth-child(2)").text(),$(row).find("td:nth-child(3)").text());
              });

				});
				$.ajax(
				{
					url: "./git-info.php",
					dataType: "text",
					success: function(data)
					{
						$('#footer-div').append(data);
					}
				});

      function editAccount( time, email, crm){
        if (email){
          $('#dlg').dialog('open').dialog('setTitle','Edit Account');
          $('#fm').form('load',{
              time: time,
              email: email,
              engagemoreid: crm
          });
        }
      }

		</script>
</head>
<body>
    <div class="wrapper">
	    <div class="content">
            <div id="page" >
                <div class="title">Monthly Report </div>
                <div id="messages">
									<div id="message"></div>
									<div id="error_div"></div>
								</div>
                <div>
                <div id='info-div'>Provide a report of the Monthly Activity</div>
            <hr/>
                <input type='hidden' id='_mon' value="<?php echo $mon ?>">
                <input type='hidden' id='_year'  value="<?php echo $year ?>">
								<center><form action="" method="post">
								<select name='month' id='month_select'>
                  <option value='1'>Jan</option>
                  <option value='2'>Feb</option>
                  <option value='3'>Mar</option>
                  <option value='4'>Apr</option>
                  <option value='5'>May</option>
                  <option value='6'>Jun</option>
                  <option value='7'>Jul</option>
                  <option value='8'>Aug</option>
                  <option value='9'>Sep</option>
                  <option value='10'>Oct</option>
                  <option value='11'>Nov</option>
                  <option value='12'>Dec</option>
                </select>
                <select name='year' id='year_select'>
                  <option value='2018'>2018</option>
                  <option value='2019'>2019</option>
                  <option value='2020'>2020</option>
                  <option value='2021'>2021</option>
                  <option value='2022'>2022</option>
                  <option value='2023'>2023</option>
                </select>
								<input type="submit" name="submit" value="Submit">
								</form>
							</center>
            </div>
              <div class="easyui-tabs" style="width:90%;height:80%">
                <div title="New Users" class="tablesorter" width="95%" class="tab">
                  <h1>New Users</h1>
                  <hr />
                  <br />
                  <table id="users" class="tablesorter" width="95%"></table>
                </div> <!-- end of tab 1 -->
								<div title="Cancelled Users" style="padding:10px" class="tab">
  								<h1>Cancelled Users</h1>
  								<hr />
  								<br />
  								<table id="cancelled" class="tablesorter" width="95%"></table>
								</div> <!-- end of tab 2 -->
                <div title="Payments Received" style="padding:10px" class="tab">
                  <h1>Payments Received</h1>
                  <hr />
                  <br />
                  <center><h2>Under Construction</h2></center>
                </div> <!-- end of tab 3 -->
              </div>

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
 		</form>
 	</div>
 	<div id="dlg-buttons">
 	    <div id="sql_buttons" class="show-sql">
 		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveAccount()" style="width:90px">Save</a>
 		<!-- a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-destoy" onclick="destroyAccount()" style="width:90px">Remove</a -->
 	   </div>
 		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
 	</div>

</body>
</html>
