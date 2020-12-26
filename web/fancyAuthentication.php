<?php

$valid_passwords = array ('woo' => 'random1');
$valid_users = array_keys($valid_passwords);
if( isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Login') {
    $user = trim($_REQUEST['userid']);
    $pass = trim($_REQUEST['passwd']);
}
if( isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Logout') {
  unset($_REQUEST['submit']);
  unset($_SESSION['loggedIn']);
  $user = "";
  $pass = "";
  echo "You have been logged out.<br />";
}

if( !isset($_SESSION['loggedIn']) ) {
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated ) {
//  header('WWW-Authenticate: Basic realm="My Realm"');
//  header('HTTP/1.0 401 Unauthorized');
//  echo "user: '".$user."', pass: '".$pass."'<br />";
  echo "You must be logged in to access the intended page.";
  unset($_REQUEST['submit']);
  unset($_SESSION['loggedIn']);
  ?>
         <center>
           <h1>EngagemoreCRM Maintenance</h1>
  <form type='POST' >
      <table>
          <tr><td>UserID:</td><td><input type='text' name='userid' /></td></tr>
          <tr><td>Password</td><td><input type='password' name='passwd' /></td></tr>
      </table>
      <br />
    <input type='submit' name='submit' value='Login' />
  </form>
</center>

  <?php
  exit;
}

// If arrives here, is a valid user.
$_SESSION['loggedIn'] = $user;
}
?>

<script type='text/javascript'>
//highlight colors for background and foreground
var	highlightColor = "#ffd700";
var	highlightFColor = "#ffffff";

//normal colors for background and foreground
var	normalColor = "#9999CC";
var	normalFColor = "#000000";

//comma delimited list of chosen item values
var     str = "";

//Highlight a menu tab
function mouseOver_Color(myObj)
{
	myObj.style.backgroundColor = highlightColor;
	myObj.style.color = highlightFColor;
	myObj.style.cursor = "hand";
}

//Show the normal menu tab
function mouseOut_Color(myObj)
{
	myObj.style.backgroundColor = normalColor;
	myObj.style.color = normalFColor;
	myObj.style.cursor = "default";
}
function disabledMenuClick(menuObj)
{
	alert('This function is not available.');
}

function isDirty()
{
  return false;
}

function goHome()
{
  document.location = './index.php?loggedin=true';
}

function logout()
{
	if (isDirty())
	{
		alert("The data you entered has not been saved. You must save or cancel to continue.");
		return;
	}

	var link = document.getElementById('sub_btn');
  link.click();
}

function showTaskList(menuObj)
{
	if (isDirty())
	{
		alert("The data you entered has not been saved. You must save or cancel to continue.");
		return;
	}
	document.location.href = 'dispatcher?event=startProcess&app=HR&process=Home';
}

function showBugList(menuObj)
{
	if (isDirty())
	{
		alert("The data you entered has not been saved. You must save or cancel to continue.");
		return;
	}
	document.location.href = 'dispatcher?event=startProcess&app=Bugs&process=BugTracker';
}

function showNoteList(menuObj)
{
	if (isDirty())
	{
		alert("The data you entered has not been saved. You must save or cancel to continue.");
		return;
	}
	document.location.href = 'dispatcher?event=startProcess&app=Notes&process=NoteTracker';
}

function supportCenter(menuObj)
{
	if (isDirty())
	{
		alert("The data you entered has not been saved. You must save or cancel to continue.");
		return;
	}
	document.location.href='http://www.blivenow.com/live/352187325499';
}

function editPrefs( ){
    document.location.href = './userPrefs.php?back=./index.php';
}
function savePrefs(){
  $('#fm').form('submit',{
    url: './update_prefs.php',
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
        $('#dlg').dialog('close');		// close the dialoG
      }
    }
  });
}

</script>

<table name="logo" cellpadding="0" cellspacing="0" width="100%" bgcolor="#DCDCF0">
  <tr>
    <td width="300" height="86" align="center" valign="middle" bgcolor="#DCDCF0">
    		<IMG SRC="./_images/green_logo.gif" width="200" height="60" alt="Logo"></td>
    <td align="right" width="100%">

      <table cellPadding="0" cellSpacing="0" width="100%" bgcolor="#9999CC" align="left">
        <tr bgcolor="#9999cc" valign="top" height="60">
         <td height="35" valign="center">
           <table cellspacing="8" cellpadding="0" border="0">
             <tr>
            </tr>
           </table>
         </td>
         <td height="35" valign="center">
           <a href="./index.php">Home</a> |
           <a href="./about.php">About</a> |
           <a href="./Consistency.php"></i>Sync</a> |
           <a href="./maintain_accounts.php">Accounts</a> |
           <a href="./maintain_users.php">Users</a> |
           <a href="./MonitorLogs.php">Logs</a> |
           <a href="./Monthly_Report.php">Report</a> |
           <a href="./Tests.php">Tests</a>
         </td>
         <td align="right" valign="middle" bgcolor="#9999CC">
            <table border="0" cellPadding="2" cellSpacing="4">
              <tr>
                <td CLASS="labelStyle" nowrap>User:</td>
                <td CLASS="fieldStyle" nowrap ONCLICK="editPrefs();"><? echo $_SESSION['loggedIn']?></td>
                <td ONCLICK="goHome();" ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img alt="Home" title="Home" src="./_images/Home.gif" width="27" height="25"></td>
                <td ONCLICK="disabledMenuClick(this);" ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img title="News" alt="News" src="./_images/News.gif" width="27" height="25"></td>
                <td ONCLICK="disabledMenuClick(this);" ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img title="Support" alt="Support" src="./_images/support.gif"  width="27" height="25"></td>
                <td ONCLICK="disabledMenuClick(this);" ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img title="Site Map" alt="Site Map" src="./_images/sitemap.gif"  width="27" height="25"></td>
                <td ONCLICK="disabledMenuClick(this);" ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img title="Products" alt="Products" src="./_images/products.gif"  width="27" height="25"></td>
                <td ONCLICK="logout();" ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img alt="Log Out" src="./_images/logout.gif"  title="Log Out" width="27" height="25"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr bgcolor="#FFFFFF" height="1">
        <td colspan="3"><img src="./_images/spacer.gif" height="1"></td>
      </tr>

      </tr>
    </table>

    </td>

  </tr>
</table>
