<?php
session_start(); //don't forget to do this
$location = "/patti/web/getLogEntriesForEmail.php";

require('fancyAuthentication.php');

?>
<html>
<!-- $Author: woo $   -->
<!-- $Date: 2017/11/14 16:37:22 $     -->
<!-- $Revision: 1.5 $ -->
<!-- $Source: /Users/woo/cvsrep/library/index.html,v $   -->
<head>
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
              url: "./ajaxLogEntriesForEmail.php",
              dataSrc: "data",
              data: { email: 'jwooten37830@icloud.com'},
          },
          columns: [
              { data: "time" , title: "Time" },
              { data: "email", title: "Email"},
              { data: "query.values", title: "Event"},
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
                <div class="title">EngagemoreCRM Users</div>
                <hr/>
                <table id="logs" class="tablesorter" width="95%"></table>

                <div id="toolbar" >
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a>
                </div>
	    		</div> <!-- end of page -->
			</div> <!-- end of content -->
			<hr />
			<div class="footer" id="footer-div"> </div>
	</div> <!-- end of wrapper -->
  </body>
  </html>
