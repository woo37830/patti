<?php
require 'config.ini.php';
session_start();

$authorized = false;
//echo $config['PHP_AUTH_USER'] . ' ' . $config['PHP_AUTH_PW'];

# LOGOUT
if (isset($_GET['logout']) && !isset($_GET["login"]) && isset($_SESSION['auth']))
{
    $_SESSION = array();
    unset($_COOKIE[session_name()]);
    session_destroy();
    echo "logging out...";
}

# checkup login and password
if (isset($config['PHP_AUTH_USER']) && isset($config['PHP_AUTH_PW']))
{

    $user = $data['username'];
    $pass = $data['password'];
    if (($user == $config['PHP_AUTH_USER']) && ($pass == ($config['PHP_AUTH_PW'])) && isset($_SESSION['auth']))
    {
      $authorized = true;
    }
}

# login
if (isset($_GET["login"]) && !$authorized ||
# relogin
    isset($_GET["login"]) && isset($_GET["logout"]) && !isset($_SESSION['reauth']))
{
    header('WWW-Authenticate: Basic Realm="Login please"');
    header('HTTP/1.0 401 Unauthorized');
    $_SESSION['auth'] = true;
    $_SESSION['reauth'] = true;
    echo "Login now or forever hold your clicks...";
    exit;
}
// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}
$_SESSION['reauth'] = null;
?>
<h1>you have <? echo ($authorized) ? (isset($_GET["login"]) && isset($_GET["logout"]) ? 're' : '') : 'not '; ?>logged in!</h1>
