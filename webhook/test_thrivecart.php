<?php
//
require 'config.ini.php';
require 'thrivecart_api.php';
require 'mysql_common.php';
require 'add_account.php';
require 'change_account_status.php';
require 'upgrade_account.php';
require 'utilities.php';
/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];

$events = array('order.success', 'order.subscription_payment', 'order.subscription_cancelled', 'order.refund');
$products = array( "product-9" => "RE - BUZZ ($69)", "product-13" => "RE - IMPACT ($99)",
                   "product-12" => "RE - BUZZ ($69)", "product-14" => "RE - IMPACT ($99)");

$first_time = array( "event" => "order.success", "account_exists" => false, "product" => "product-9");
$cancel = array("event" => "order.subscription_cancelled", "account_exists" => true, "account_isInactive" => false, "product" => "product-9");
$reactivate = array("event" => "order.success", "account_exists" => true, "account_isInactive" => true, "product" => "product-9");
$upgrade = array("event" => "order.success", "account_exists" => true, "account_isInactive" => false, "product" => "product-13");
$tests = array("first_time" => $first_time, "cancel" => $cancel, "re-activate" => $reactivate, "upgrade" => $upgrade);
//$tests = array("re-activate" => $reactivate, "upgrade" => $upgrade);
$myFile = "test.txt";
$date = (new DateTime('NOW'))->format("y:m:d h:i:s");
if( $fh = fopen($myFile, 'a') ) {
fwrite($fh, "\n-----------------".$date."-----------------------------------\n");
}
fwrite($fh, "\naccount_id = '" . $account_id . "'\n");
$email = 'test8@test.com';
foreach( $tests as $key => $value ) {
  $event = $value['event'];
  echo "\n------------Test case - " . $key . " -----------------------\n";
  fwrite($fh, "\n------------Test case - " . $key . " -----------------------\n");
if( !in_array($event, $events) ) {
  fwrite($fh, "event is " . $event . "\n");
  logit($email, $event, "Invalid event");
  fclose($fh);
  die();
}

//$charges = $order['charges'];
$data = $value['product'];
if( empty($data) ) {
  logit($email, "", "No subscriptions");
  fclose($fh);
  die();
}
fwrite($fh,"data:".$data."\n");


  $product = $data;
 // here we put other choices and set the product
  fwrite($fh,"\nThe item_identifier is '".$product."'\n");

if( array_key_exists($product, $products) ) { // Here is where we check that we have the correct product
  $thrivecartid = $email;
  fwrite($fh,"\nThrivecart customer_id is: ".$thrivecartid."\n");
  fwrite($fh,"\nProcessing item_identifier: '".$product."'\n");
  $group_name = $products[$product];
  fwrite($fh, "\nThe group will be: ".$group_name."\n");
  echo "\nFor $thrivecartid we are setting product to $product with name $group_name\n";


$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

if( $event == "order.success")
{
  if( account_exists($value, $thrivecartid) )
  {
    if( account_isInactive($value, $thrivecartid) )
    {
      // reactivate account
      reactivate_account($thrivecartid, $api_endpoint, $account_id, $api_key);
    }
    else
    {
      // account is active
      if( product_isTheSame($thrivecartid, $product) )
      {
        // It is a payment and just let it go.
        fwrite($fh, "\nThis is just a payment, let it go.\n");
      }
      else
      {
        // different product, then cnange the group for the account
        $account = array(
            'password'  => 'engage123',
          );
        echo "\nProduct needs to be changed to $product\n";
        $result = change_account_group($thrivecartid, $api_endpoint, $account_id, $api_key,
         $group_name, $product);
        fwrite($fh, $result . "\n");
      }
    }
  }
  else
  { // account does not exist
    /**
     * The contact information to insert.
     *
     * Information will be added to your AllClients contacts!
     */
    $account = array(
      	'password'  => 'engage123',
      );
      add_account($api_endpoint, $account_id, $api_key, $account, $group_name, $thrivecartid, $email);
  }
}
  else if( $event == "order.subscription_cancelled")
  {
    fwrite($fh, "\nProcessing subscription_cancelled\n");
    $result = change_account_status($api_endpoint,$account_id, $api_key, $thrivecartid,0);
    fwrite($fh, $result . "\n");
  }
}
else
  {
    fwrite($fh, "\nInvalid product '" . $product . "'\n");
    //logit($_REQUEST['customer']['email'], json_encode($_REQUEST), "failure - Invalid product");
  }
}
fwrite($fh,"\n------------------All Done!-----------------\n");
fclose($fh);
http_response_code(200);
//die();



?>
