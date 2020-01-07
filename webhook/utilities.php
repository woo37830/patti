<?php

/**
 * A set of functions used in tests and in the main thrivecart.php application
 */

function account_exists($fh, $value, $thrivecartid) {
  fwrite($fh, "\nProcessing order.success");
  //return $value['account_exists'];
  $acct_id = getAccountId( $thrivecartid );
  return $acct_id != -1;
}
function account_isInactive($fh, $value, $thrivecartid) {
  fwrite($fh, "\nChecking account status");
  $id = getAccountId($thrivecartid);
  echo "Obtained engagemoreid = '" . $id . "'\n";

  $saved_status = getStatusFor($id);

  //  return $value['account_isInactive'];
  fwrite($fh, "\ngetStatusFor returned '" . $saved_status . "'");
  echo "\naccount is $saved_status\n";
  return $saved_status == 'inactive';
}
function reactivate_account($fh, $thrivecartid, $api_endpoint, $account_id, $api_key) {
  $accountid = getAccountId( $thrivecartid );
  change_account_status($fh, $api_endpoint,$account_id, $api_key, $thrivecartid,1);
}
function change_account_group($fh, $thrivecartid, $api_endpoint, $account_id, $api_key, $group_name, $productid) {
  echo "\nchange_account_group for $thrivecartid\n";
  $accountid = getAccountId( $thrivecartid );
  echo "\naccountid is $accountid\n";
  upgrade_account($api_endpoint, $account_id, $api_key, $accountid,
   $group_name, $productid, $thrivecartid);
}
function product_isTheSame($fh, $thrivecartid, $product) {
  $saved_product = getProductFor( $thrivecartid );
  echo "\nCurrent product is $saved_product, specified product is $product\n";
  return $product == $saved_product;
}
function pretty_dump($mixed = null) {
  ob_start();
  echo json_encode($_REQUEST, JSON_PRETTY_PRINT);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

//file_put_contents($file, var_dump_ret($_REQUEST));
function dump_response($msg = null) {
  $eFile = "error.txt";
  $date = (new DateTime('NOW'))->format("y:m:d h:i:s");
  if( $err = fopen($eFile, 'a') ) {
	  fwrite($err, "\n-----------------".$date."-----------------------------------\n");
	  fwrite($err, $msg."\n");
	  fwrite($err,"JSON DUMP\n");
	  fwrite($err, pretty_dump($_REQUEST));
	  fwrite($err,"\nEND OF DUMP\n");
	  fclose($err);
	}
  return;
}

?>
