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
  <form type='POST' >
      <table>
          <tr><td>UserID:</td><td><input type='text' name='userid' /></td></tr>
          <tr><td>Password</td><td><input type='password' name='passwd' /></td></tr>
      </table>
      <br />
                  <input type='submit' name='submit' value='Login' />
  </form>
  <br /><br /><a href='index.php' />Home</a>
                 </center>

  <?php
  die;
}

// If arrives here, is a valid user.
$_SESSION['loggedIn'] = $user;
}
echo "<p>Welcome ".$_SESSION['loggedIn']."</p>";
echo "<br />";


?>
<form type='POST' action="<?=$action ?>" >
  <input type='submit' name='submit' value='Logout' />
</form>
