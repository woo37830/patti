<?php

/**
 * A set of functions used in tests and in the main thrivecart.php application
 */

function account_exists($value, $thrivecartid) {
  //return $value['account_exists'];
  $acct_id = getAccountId( $thrivecartid );
  return $acct_id != -1;
}

function account_isInactive($value, $thrivecartid) {
  $id = getAccountId($thrivecartid);
  $saved_status = getStatusFor($id);
  //  return $value['account_isInactive'];
  return $saved_status == 'inactive';
}

function reactivate_account($thrivecartid, $api_endpoint, $account_id, $api_key) {
  $accountid = (int)getAccountId( $thrivecartid );
  if( $accountid != -1 ) {
    change_account_status($api_endpoint,$account_id, $api_key, $thrivecartid,1);
  } else {
    logit($thrivecartid, "","FAILURE in reactivate: Did not find email for $thrivecartid");
  }
}

function change_account_group($thrivecartid, $api_endpoint, $account_id, $api_key, $group_name, $productid) {
  $accountid = (int)getAccountId( $thrivecartid );
  if( $accountid != -1 ) {
      upgrade_account($api_endpoint, $account_id, $api_key, $accountid,
        $group_name, $productid, $thrivecartid);
   } else {
     logit($thrivecartid, "","FAILURE in change_account_group: Did not find email for $thrivecartid");
   }
   return $accountid;
}
function product_isTheSame($thrivecartid, $product) {
  $saved_product = getProductFor( $thrivecartid );
  return $product == $saved_product;
}

?>
