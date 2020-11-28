<?php
require '../webho/config.ini.php';
require '../webhook/mysql_common.php';
require '../webhook/utilities.php';
require '../webhook/get_accounts.php';



$today = date("D M j G:i:s T Y");

echo "<center>$today<br /><hr />";
if(isset($_POST['submit'])){
    $from = $_POST['email'];
    if( account_exists($from) )
    {
      $userId = getAccountId($from);
      echo "<br />Email: $from, user: $userId<br />";
    }
    else
    {
      echo "<br />No account in users_db.users for $from<br /><br />";
      $results = getAccounts($today, $from);
      if( !$results ) {
        echo "<br />Error getting accounts</br />";
        var_dump($results);
      } else {
        echo "<br />Results in getAccountsId.php<br />";
          var_dump($results);
          echo "<br />List of results<br />";
          $k = 0;
          foreach( $results as $account ) {
            echo "<br />$k) $account->accountid  $account->email  $account->status<br />";
            $k++;
        }
      }
    }
    echo "</center>";// this is the sender's Email address
    }
?>

<!DOCTYPE html>
<head>
<title>AccountID</title>
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
  <h1>Get Account ID</h1>
  <hr />
  <br />
<form action="" method="post">
Email: <input type="text" name="email"><br /><br />
<input type="submit" name="submit" value="Submit">
</form>
<br /><br /><a href='index.php' />Home</a>
</center>
<div id="footer">
  <!-- Footer start -->
<?php
  include '../webhook/git-info.php';
?>
  <p>Last Update: 2020-03-13 11:07    <a href="mailto:jwooten37830@me.com?Subject=EngagemoreCRM%20Problem">webmaster</a>
  <!-- Footer end -->
</div>

</body>
</html>
