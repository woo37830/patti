<?php

$action = "http://logout:logout@" . $_SERVER['HTTP_HOST'] . $location;
$valid_passwords = array ('woo' => 'random1');
$valid_users = array_keys($valid_passwords);
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
if( isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Logout') {
  $user = 'test';
  $pass = 'test';
}

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated) {
  header('WWW-Authenticate: Basic realm="My Realm"');
  header('HTTP/1.0 401 Unauthorized');
  echo "Not authorized";
  unset($_REQUEST['submit']);
  unset($_SESSION['loggedIn']);
  ?>
  <form type='POST' action="<?=$action ?>" >
    <input type='submit' name='submit' value='Login' />
  </form>
  <?php
  die;
}

// If arrives here, is a valid user.
$_SESSION['loggedIn'] = $user;
echo "<p>Welcome $user.</p>";
echo "<br />";


?>
<form type='POST' action="<?=$action ?>" >
  <input type='submit' name='submit' value='Logout' />
</form>
