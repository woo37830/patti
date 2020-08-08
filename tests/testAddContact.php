<?php
// Test the addContactNote
session_start(); //don't forget to do this
$location = "/patti/tests/testAddContact.php";
if( !isset($_SESSION['auth']) ) {
  require('fancyAuthentication.php');
}


?>
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
  <h1>Add Contact</h1>
<?php
require '../webhook/add_contact.php';
require '../webhook/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';

$today = date("D M j G:i:s T Y");

echo "<center>$today<br /><hr />";
if(isset($_POST['submit'])){
    $from = trim($_POST['email']);
    $new_contact = trim($_POST['contact']);
    if( strlen($from) > 0 && strlen($new_contact) > 0 && account_exists($from) ) {
        $result = addContact($today, $from, $new_contact);
        echo "<br />Result addedContact $new_contact to $from: <br />Contact ID: $result <br />\n";
    } else {
        echo "<br />You must provide an account and a contact email!<br />";
    }
}
?>
  <hr />
  <br />
<form action="" method="post">
Account Email: <input type="text" name="email"><br />
Contact Email: <input type="text" name ="contact"><br /><br />
<input type="submit" name="submit" value="Submit">
</form>
<br /><br /><a href='index.php' />Home</a>
</center>
<footer>
  <!-- Footer start -->
<?php
  include '../webhook/git-info.php';
?>
  <p>Last Update: 2020-03-13 11:07    <a href="mailto:jwooten37830@me.com?Subject=EngagemoreCRM%20Problem">webmaster</a>
  <!-- Footer end -->
</footer>

</body>
</html>