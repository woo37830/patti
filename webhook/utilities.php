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

function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
}
// firstAndLastFromEmail returns an array with:
//  0) First Name
//  1) Last Name
//  2) actual email address xxx@yyy.zzz
//
function firstAndLastFromEmail($email)
{
  $displayname = trim(get_displayname_from_rfc_email($email));
//  echo "\n$displayname\n";
  $email = get_email_from_rfc_email($email);
  $pieces = array();
  $surName = explode(",", $displayname);
//  print_r($surName);
  if( sizeof( $surName ) > 1 ) {
    $displayname = $surName[0];
  }
  $thePieces = explode(" ", $displayname);
//  print_r($thePieces);
  switch( sizeof($thePieces) )
  {
    case 4:
    case 3:
    case 2:
      $pieces[0] = $thePieces[0];
      $pieces[1] = $thePieces[sizeof($thePieces)-1];
      break;
    case 1:
      $pieces[0] = $thePieces[0];
      $pieces[1] = 'Last';
      break;
    case 0:
      $pieces = $thePieces;
      break;
  }
  array_push($pieces, $email);
//  print_r($pieces);
  return $pieces;
}
function get_displayname_from_rfc_email($rfc_email_string) {
    // match all words and whitespace, will be terminated by '<'
    $pos = strstr($rfc_email_string, '<');
    if( $pos !== false )
    {
      $name = delete_all_between('<','>', $rfc_email_string);
      return $name; // return what's not between < and >
    }
    else
    { // there is no < > in the email string
      $pos = strstr($rfc_email_string, ' ');
      if( $pos != false )
      {
        $pieces = explode(" ", $rfc_email_string);
        return $pieces[0];
      }
      $parts = explode("@", $rfc_email_string);
      $pos = strstr($parts[0], '.');
      if( $pos != false )
      {
        $pieces = explode(".", $parts[0]);
        $pieces[0] = ucfirst($pieces[0]);
        $pieces[1] = ucfirst($pieces[1]);
        return "$pieces[0] $pieces[1]";
      }
      return "First Last";
    }
}
// Output: My Test Email

function get_email_from_rfc_email($rfc_email_string) {
    // extract parts between the two angle brackets
    $pos = strstr($rfc_email_string, '<');
    if( $pos !== false )
    {
      $mailAddress = preg_match('/(?:<)(.+)(?:>)$/', $rfc_email_string, $matches);
      return $matches[1];
    }
    $pos = strstr($rfc_email_string, ' ');
    if( $pos != false )
    {
      $pieces = explode(" ", $rfc_email_string);
      return $pieces[sizeof($pieces) -1 ];
    }
    return $rfc_email_string;
}
?>
