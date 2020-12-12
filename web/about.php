<?php
session_start(); //don't forget to do this
$location = "/patti/web/about.php";

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
<div class='content'>
  <div id='page'>
  <center>
    <h1>About</h1>
    <hr />
    <br />
		<pre>
			Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
sed diam nonummy nibh euismod tincidunt ut laoreet dolore
magna aliquam erat volutpat. Ut wisi enim ad minim veniam,
quis nostrud exerci tation ullamcorper suscipit lobortis
nisl ut aliquip ex ea commodo consequat.

Duis autem vel eum iriure dolor in hendrerit in vulputate
velit esse molestie consequat, vel illum dolore eu feugiat
nulla facilisis at vero eros et accumsan et iusto odio
dignissim qui blandit praesent luptatum zzril delenit augue
duis dolore te feugait nulla facilisi.Lorem ipsum dolor sit
amet, consectetuer adipiscing elit, sed diam nonummy nibh
euismod tincidunt ut laoreet dolore magna aliquam erat
volutpat.
	  </pre>
  </center>
</div> <!-- end of page -->
</div> <!-- end of content div -->
<hr />
<div id="footer-div" class="footer"></div>
</div> <!-- end of wrapper -->
<div id='logoutDiv' style='display:block' align='right'>
   <form id='logoutForm' type='POST' action="./index.php?<?=$action ?>" >
     <input type='submit' id='sub_btn' name='submit' value='Logout'  />
   </form>
 </div>
 <div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
   <div class="ftitle">Preferences</div>
   <form id="fm" method="post" novalidate>
     <div class="fitem">
       <label for="user">User:</label>
       <input name="user" class="easyui-textbox" required="true">
     </div>
     <div class="fitem">
       <label for="skin">Skin:</label>
       <input name="skin" class="easyui-textbox" required="true">
     </div>
     <div class="fitem">
       <label for="logo">Logo:</label>
       <input name="logo" class="easyui-textbox">
     </div>
   </form>
 </div>
 <div id="dlg-buttons">
     <div id="sql_buttons" class="show-sql">
   <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePrefs()" style="width:90px">Save</a>
   <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
 </div>

</body>
</html>
