<?php

/**
 * A set of functions used in tests and in the main thrivecart.php application
 */

function account_exists($thrivecartid) {
  //return $value['account_exists'];
  $acct_id = getAccountId( $thrivecartid );
  return $acct_id != -1;
}

function account_isInactive($thrivecartid) {
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

function getProductId() {
   $pmf = (int)$_REQUEST['base_product'];
   $product = "product-$pmf";
   return $product;
}

function getInvoiceId() {
  return $_REQUEST['invoice_id'];
}

function getOrderId() {
  return $_REQUEST['order_id'];
}

function getProductName($product, $email, $json_data) {

  require 'product_data.php';

  if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
    return $products[$product];
  }
  logit($email, $json_data, "Invalid product: $product");
  die("Invalid product: $product");
}

function firstAndLastFromEmail($email)
{
  $displayname = get_displayname_from_rfc_email($email);
//  echo "\n$displayname\n";

  $pieces = explode(" ", $displayname);
//  echo "\n$pieces[0], $pieces[1]\n";

  $address = $email;
  $pos = strstr($email, '<');
  if( $pos !== false )
  {
    $address = get_email_from_rfc_email($email);
  }
  array_push($pieces,$address);
  return $pieces;
}
function get_displayname_from_rfc_email($rfc_email_string) {
    // match all words and whitespace, will be terminated by '<'
    $pos = strstr($rfc_email_string, '<');
    if( $pos !== false )
    {
      $name       = preg_match('/[\w\s]+/', $rfc_email_string, $matches);
      $matches[0] = trim($matches[0]);
      return $matches[0];
    }
    else
    {
      return "First Last";
    }
}
// Output: My Test Email

function get_email_from_rfc_email($rfc_email_string) {
    // extract parts between the two angle brackets
    $mailAddress = preg_match('/(?:<)(.+)(?:>)$/', $rfc_email_string, $matches);
    return $matches[1];
}
?>
