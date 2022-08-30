<?php
// Prepare a monthly report on new users, cancelled users, and commissions earned
// Expect a month argument like 2 for February, or if none, then current month.
//
require 'webhook/config.ini.php';
require 'webhook/mysql_common.php';
require 'webhook/utilities.php';

$months = array('','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
           'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$mon = (int)date("m");
if(isset($_POST['submit']) && strlen($_POST['month']) > 0 ){
    $mon = $_POST['month'];
  }
$cr = "\n";
$h1="";
$h1end="";
$h2 = "";
$h2end = "";
if(!defined('STDIN') ) {
  $cr = "<br />";
  $h1 = "<center><h1>";
  $h1end = "</h1></center>";
  $h2 = "<center><h2>";
  $h2end = "</h2></center>";

} else {
  if( sizeof( $argv ) > 1 ) {
    $mon = (int)$argv[1];
  }
}
if( isset($_REQUEST['month']) && !isset($_POST['month'])) {
  $mon = (int)$_REQUEST['month'];
}
echo $h1 . "Billing Report for: $months[$mon]" . $h1end . $cr;

$cmd = "time_track_report.pl -client=patti -year=2020 -mon=$mon";
$old_path = getcwd();
chdir('/Users/woo/bin/');
$output = shell_exec("/Users/woo/bin/time_track_report.pl -client=patti -year=2020 -mon=$mon");
chdir($old_path);

  echo "<div id='content'><pre>".$output."</pre></div>";

//  echo "<div id='footer' ><hr /><em>";

//    include '/Library/WebServer/Documents/patti/webhook/git-info.php';
//  echo "</em></div>";



?>
<!DOCTYPE html>
<head>
<title>Billing Report</title>
<style media="screen" type="text/css">
html,
body {
  margin:0;
  padding:0;
  height:100%;
}
#container {
  min-height:100%;
  position:relative;
}
#content {
  margin-left: 2cm;
}
#header {
  background:#fff;
  padding:10px;
}
#body {
  padding:10px;
  padding-bottom:60px;	/* Height of the footer */
}
#footer {
  position:absolute;
  bottom:0;
  width:100%;
  height:60px;			/* Height of the footer */
  background:#6cf;
}
/* other non-essential CSS */
#header p,
#header h1 {
  margin:0;
  padding:10px 0 0 10px;
}
#footer p {
  margin:0;
  padding:10px;
}
</style>
</head>
<body>
<center>
  <br />
<form action="" method="post">
Month(e.g. 6): <input type="text" name="month"><br /><br />
<input type="submit" name="submit" value="Submit">
</form>
<br /><br /><a href='./tests/index.php' />Home</a>
</center>
<div id="footer">
  <!-- Footer start -->
<?php
  include 'webhook/git-info.php';
?>
  <p>Last Update: 2020-03-13 11:07    <a href="mailto:jwooten37830@me.com?Subject=EngagemoreCRM%20Problem">webmaster</a>
  <!-- Footer end -->
</div>
</body>
</html>
