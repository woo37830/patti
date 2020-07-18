<?php
require '../webhook/config.ini.php';
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
<title>Get Accounts</title>
</head>
<body>
<center>
  <br />
  <h1>Get Accounts</h1>
  <hr />
  <br />
<form action="" method="post">
Email: <input type="text" name="email"><br /><br />
<input type="submit" name="submit" value="Submit">
</form>
</center>
</body>
</html>
