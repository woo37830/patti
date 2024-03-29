<?php
  session_start(); //don't forget to do this
  $location = "/patti/tests/testAddContact.php";

  require('fancyAuthentication.php');


  function getHTML() {
  $html = <<<EOS
<html>
    <head>
          <style type="text/css" media="screen">
html {
/*      background-color: #c8dcff; */
 }

body
{
    color:#404040;
/*   background-color: powderblue; */
    font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
}

#page
{
  position: relative;
  min-height: 100%;
}

#content
{
  padding-bottom: 1.5rem; /* Footer height */
}

footer
{
  width: 100%;
  position: absolute;
  bottom: 0;
  padding-top: 3px;
  background-color: powderblue;
  text-align: center;
  height: 1.5rem;
  font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
  font-style: italic;
}
</style>
    </head>
<body>
<center>
  <br />
  <h1>Add Contact Using Email</h1>
EOS;
  return $html;
}

function getHTML2() {
$html2 = <<<EOS2

  <hr />
  <br />
<form action="" method="post">
Account Email: <input type="text" name="email"><br />
Contact Email: <input type="text" name ="contact"><br /><br />
<input type="submit" name="submit" value="Submit">
</form>
<br /><br /><a href='index.php' />Home</a>
</center>
EOS2;
  return $html2;
}

require '../webhook/add_contactFromEmail.php';
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';

$today = date("D M j G:i:s T Y");

echo "<center>$today<br /><hr />";
if(isset($_POST['submit'])){
    $from = trim($_POST['email']);
    $new_contact = trim($_POST['contact']);
    if( strlen($from) > 0 && strlen($new_contact) > 0 && account_exists($from) ) {
        $result = addContactFromEmail($today, $from, $new_contact, "tests");
        echo "<br />Result addedContact $new_contact to $from: <br />Contact ID: $result <br />\n";
    } else {
        echo "<br />You must provide an account and a contact email!<br />";
    }
}


echo getHTML();
echo getHTML2();
include '../webhook/git-info.php';
echo "</footer></div></body></html>";
?>
