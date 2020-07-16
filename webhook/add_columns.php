<?php
// Add columns to users table to hold order_id and invoice_id
// Get those values from the logs table for live order.success
// and use the email from the returned json to identify the user
// in the users table.
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
require 'upgrade_account.php';
require 'utilities.php';

$dbase = $config['PATTI_DATABASE'];
if( $conn = connect($dbase) ) {
    $table = $config['PATTI_LOG_TABLE'];

    $query = "SELECT request_json  FROM " . $table . " WHERE `request_json` LIKE '%order.success%' and `request_json` LIKE '%live%' ";
    $results_array = array();
    $rows = $conn->query($query)  or die($conn->error);
    while( $row = $rows->fetch_assoc() ) {
       array_push($results_array, $row['request_json']);
    }
    if( !empty( $results_array[0] ) ) {
      $k = 0;
      foreach ( $results_array as $result ) {
      //  echo $k . ") " . $result . "\n\n";
      $json = json_decode($result, true);
      $email = $json['customer']['email'];
      $accountid = getAccountId($email);
      $invoiceid = $json['invoice_id'];
      $orderid = $json['order_id'];
      if( addOrderAndInvoiceIds($accountid, $orderid, $invoiceid) ) {
        $k++;
         echo $k . ") order_id: " . $orderid . ", invoice_id: " . $invoiceid . ", email: " . $email . "\n";
      } else {
        echo "Failure adding order and invoice id for $email\n";
      }
      }
    }
    $rows -> close();
    $conn->close();
    // Do something
}
echo "$k records sucessfully processed.\n";
echo "\nAll Done!\n";
?>
