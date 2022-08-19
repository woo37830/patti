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
  <h1>Test Abandon Cart</h1>
EOS;
return $html;
}
function getHTML2() {
$html2 = <<<EOS2
  <hr />
  <br />
<form action="" method="post">
First Name: <input type="text" name="first"><br />
Last Name: <input type="text" name="last"><br />
Email: <input type="text" name="email"><br />
<input type="submit" name="submit" value="Submit">
</form>
<br /><br /><a href='index.php' />Home</a>
</center>
EOS2;
  return $html2;
}

require '../webhook/add_contact.php';
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';

$today = date("D M j G:i:s T Y");
$agentId = $config['PATTI_CANCEL_CART_USER'];

echo "<center>$today<br />";
echo "Abandon cart emails added to account: $agentId<br /><hr />";
if(isset($_POST['submit'])){
    $source = $config['PATTI_CANCEL_CART_SOURCE'];
    $firstName = trim($_POST['first']);
    $lastName = trim($_POST['last']);
    $email = trim($_POST['email']);
    $result = addContact($today, $agentId, $firstName, $lastName, $email, $source);
    echo "<br />added abandoned cart $email: $result <br />\n";

}
echo getHTML();
echo getHTML2();
include '../webhook/git-info.php';
echo "</footer></div></body></html>";
?>
