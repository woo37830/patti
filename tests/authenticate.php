<?php
require 'config.ini.php';
if( isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Login') {
  $user = $_REQUEST['userid'];
  $pw = $_REQUEST['password'];
  $valid_response = false;
  if(($user == 'woo') &&
    ($pw == 'random1')) {
    $valid_response = true;
  }

if ( !$valid_response ) {
    echo 'Wrong Credentials!';
    exit;
  }

// ok, valid username & password
//echo 'You are logged in as: ' . $data['username'];
$_SESSION['loggedIn'] = $data['username'];
exit;
}


?>
<html>
<head>
  <style type="test/css">
  #content {
    width: 50%;
    border: 1px solid blue'
  }
</head>
<body>
  <div id='content'>
  <center>
  <form type='POST' >
    <table>
    <tr><td>UserId</td><td><input type='text' name='userid' value='' /></td></tr>
    <tr><td>Password</td><td><input type='password' name='password' value='' /></td></tr>
    </table>
    <input type='submit' name='submit' value='Login' />
  </form>
</center>
</div>
</body>
</html>
