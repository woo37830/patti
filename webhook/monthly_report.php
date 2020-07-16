<?php
// Prepare a monthly report on new users, cancelled users, and commissions earned
// Expect a month argument like 2 for February, or if none, then current month.
//
require 'config.ini.php';
require 'mysql_common.php';
require 'utilities.php';
require 'product_data.php';

$months = array('','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
           'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$mon = (int)date("m");
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
if( isset($_REQUEST['month']) ) {
  $mon = (int)$_REQUEST['month'];
}
echo $h1 . "Monthly Report for: $months[$mon]" . $h1end . $cr;
echo $h2 . "Accounts Added" . $h2end . $cr;

$dbase = $config['PATTI_DATABASE'];
if( $conn = connect($dbase) ) {
    $table = $config['PATTI_USERS_TABLE'];
    $query = "SELECT email, invoiceid, orderid, added,MONTH(added)  as 'added. MONTH' "
    . " FROM " . $table
    . " WHERE MONTH(added) =  " . $mon . " AND status = 'active'";
    $results_array = array();
    $rows = $conn->query($query)  or die($conn->error);
    $k = 0;
    if(!defined('STDIN') ) {
      echo "<center>";
      echo "<table cellpadding='5'><thead><tr><th>Date</th><th>Email</th><th>Invoice</th><th>Order</th></tr></thead><tbody>";
    }
    while( $row = $rows->fetch_assoc() ) {

      $k++;
            //  echo $k . ") " . $result . "\n\n";
      $email = $row['email'];
      $added = $row['added'];
      $invoiceid = $row['invoiceid'];
      $orderid = $row['orderid'];
    //  $invoiceid = $json['invoice_id'];
    //  $orderid = $json['order_id'];
    if(!defined('STDIN') ) {
      echo "<tr><td>$added</td><td>$email</td><td>$invoiceid</td><td>$orderid</td></tr>";
    } else {
    echo $k . ") Added: $added , Email: $email, Invoice: $invoiceid, Order: $orderid" . $cr;
  }
}
    if( !defined('STDIN') ) {
      echo "</tbody></table><hr />Total Added: $k</center>";
    } else {
      echo $cr . "Total Added: $k" . $cr;
    }
    $rows -> close();
    //$conn->close();

  }
// Handle looking through logs for order.subscription_cancelled for live for month

echo $h2 . "Cancellations ths month" . $h2end . $cr;

$table = $config['PATTI_LOG_TABLE'];
$query = "SELECT email, request_json, received,MONTH(received)  as 'received. MONTH' "
. " FROM " . $table
. " WHERE MONTH(received) =  " . $mon . " AND status LIKE '%Subscription_cancelled, result: Succeeded%'";
$results_array = array();
$rows = $conn->query($query)  or die($conn->error);
$k = 0;
$total = 0;
if(!defined('STDIN') ) {
  echo "<center>";
  echo "<table cellpadding='5'><thead><tr><th>Date</th><th>Email</th></tr></thead><tbody>";
}
while( $row = $rows->fetch_assoc() ) {
  $received = $row['received'];
  $request = $row['request_json'];
  $email = $row['email'];
  $k++;
  if( !defined('STDIN') ) {
    echo "<tr><td>$received</td><td>$email</td></tr>";
  }
  else {

  }
}
  if( !defined('STDIN') ) {
    echo "</tbody></table><hr />Total Cancellations: $k</center>";
  } else {
    echo $cr . "Total Commissions: $k" . $cr;
  }
  $rows -> close();

echo $h2 . "Payments This Month" . $h2end . $cr;

$table = $config['PATTI_LOG_TABLE'];
$query = "SELECT email, request_json, received,MONTH(received)  as 'received. MONTH' "
. " FROM " . $table
. " WHERE MONTH(received) =  " . $mon . " AND request_json LIKE '%order.subscription_payment%'";
$results_array = array();
$rows = $conn->query($query)  or die($conn->error);
$k = 0;
$total = 0;
if(!defined('STDIN') ) {
  echo "<center>";
  echo "<table cellpadding='5'><thead><tr><th>Date</th><th>Email</th><th>Amount</th><th>Product</th></tr></thead><tbody>";
}
while( $row = $rows->fetch_assoc() ) {
  $received = $row['received'];
  $request = $row['request_json'];
  $json = json_decode($request, true);
  $email = $json['customer']['email'];
  $amount = $json['subscription']['amount']/100;
  $product = "product-" . $json['subscription']['id'];
  if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
    $group_name = $products[$product];
    $total += $amount;
  $k++;
  if( !defined('STDIN') ) {
    echo "<tr><td>$received</td><td>$email</td><td>$amount</td><td>$group_name</td></tr>";
  }
  else {

  }
}
}
  if( !defined('STDIN') ) {
    echo "</tbody></table><hr />Total Received: $total</center>";
  } else {
    echo $cr . "Total Commissions: $k" . $cr;
  }
  $rows -> close();

echo $h2 . "Affiliate Commissions Earned" . $h2end . $cr;

$table = $config['PATTI_LOG_TABLE'];
$query = "SELECT request_json, received,MONTH(received)  as 'received. MONTH' "
. " FROM " . $table
. " WHERE MONTH(received) =  " . $mon . " AND email = 'affiliate.commission_earned'";
$results_array = array();
$rows = $conn->query($query)  or die($conn->error);
$k = 0;
$total = 0;
if(!defined('STDIN') ) {
  echo "<center>";
  echo "<table cellpadding='5'><thead><tr><th>Date</th><th>Email</th><th>Invoice</th><th>Amount</th><th>Product</th></tr></thead><tbody>";
} else {
  echo "\tDate\tEmail\tInvoice\tAmount$cr";
}
while( $row = $rows->fetch_assoc() ) {
  $received = $row['received'];
  $request = $row['request_json'];
  $json = json_decode($request, true);
  $amount = (int)$json['commission_amount']/100;
  $product = $json['related'];
  // see if product is in list of products
  $pieces = explode('-', $product);
  $product = $pieces[1] . '-' . $pieces[2];
  if( array_key_exists($product, $products) ) {
    $total += $amount;
    $group_name = $products[$product];
    $invoiceid = (int)$json['invoice_id'];
    $email = getEmailForInvoiceId($invoiceid);
    $k++;
    if( !defined('STDIN') ) {
      echo "<tr><td>$received</td><td>$email</td><td>$invoiceid</td><td>$amount</td><td>$group_name</td></tr>";
    }
    else {
      echo "\t" . $received . "\t" . $email . "\t" . $invoiceid . "\t" . $amount . "\t" . $group_name . $cr;
    }
  }
}
  if( !defined('STDIN') ) {
    echo "</tbody></table><hr />Total Commissions: $k          Total Amount: $total";
    echo "<br /><em>Note: Unknown emails are due to missing data on invoice when signing up.</em></center>";
    echo "<div id='footer' ><hr /><em>";

      include 'git-info.php';
    echo "</em></div>";
} else {
    echo $cr . "Total Commissions: $k" . " Total Amount: $total" . $cr;
    echo $cr . "Note: Unknown emails are due to missing data on invoice when signing up." . $cr;
  }
  $rows -> close();


// Handle affiliate.commission_earned for month, gather by orderid or invoiceid
echo $cr . "All Done!" . $cr;
?>
