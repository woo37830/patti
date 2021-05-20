<?php
require './users.php';
//$valid_passwords = array ('woo' => 'random1');
$valid_users = array_keys($users);
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

$user = 'woo';
$pass = 'random1';

if( !isset($_SESSION['loggedIn']) ) {
$validated = (in_array($user, $valid_users)) && ($pass == $users[$user]['passwd']);

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
$_SESSION['role'] = $users[$user]['role'];
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
var parameters = new URLSearchParams(window.location.search);

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

var parameters = new URLSearchParams(window.location.search);

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

function goBack(menuObj)
{
	if (isDirty())
	{
		alert("The data you entered has not been saved. You must save or cancel to continue.");
		return;
	}

  if( parameters.get('back') != null )
  {
    window.location = parameters.get('back');
  }
  else
  {
    window.location = "./index.php";
  }

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

function showInfo(menuObj)
{
  var state = document.getElementById('info-div').style.display; // get the reference of the div
  if (state == 'block') {
        document.getElementById('info-div').style.display = 'none';
    } else {
        document.getElementById('info-div').style.display = 'block';
    }
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
         <td valign="middle">
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
                <td ONCLICK="goBack();" ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img title="Prev" alt="Prev" src="./_images/previous.gif" width="27" height="25"></td>
                <td id='info-img' ONMOUSEOVER="mouseOver_Color(this);" ONMOUSEOUT="mouseOut_Color(this);" nowrap><img title="Info" alt="Info" src="./_images/icon_question.gif"  width="27" height="25"></td>
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
