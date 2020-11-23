<?php
//require 'config.ini.php';
require 'get_users.php';

date_default_timezone_set("America/New_York");

$today = date("D M j G:i:s T Y");
/*
function getAUser( $email, $users ) {
  foreach($users as $user) {
    if( $user['email'] == $email ) {
      return $user;
    }
  }
  return -1;
}
function getAccountById( $id, $accounts ) {
  foreach( $accounts as $account ) {
    if( $account->id == $id ) {
      return $account;
    }
  }
  return -1;
}
function getAccountByEmail( $email, $accounts ) {
  foreach( $accounts as $account ) {
    if( $account->email == $email ) {
      return $account;
    }
  }
  return -1;
}
$result = array();
*/

function getTheAccounts()
{
  require 'config.ini.php';
  require '../webhook/thrivecart_api.php';

  //require 'mysql_common.php';
  //require 'utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'GetAccounts.aspx';

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'showpendingdelete' => 1,
    'getgroup' => 1
  );
  $results_xml = thrivecart_api($url, $data); // returns simplexml_load_string object representation

  /**
   * If an API error has occurred, the results object will contain a child 'error'
   * SimpleXMLElement parsed from the error response:
   *
   *   <?xml version="1.0"?>
   *   <results>
   *     <error>Authentication failed</error>
   *   </results>
   */
  // $json = json_encode($results_xml);
  // echo $json;
  if (isset($results_xml->error)) {
    echo json_encode(array('errorMsg'=>'Some errors occured: '+$results_xml->error));
    return $results_xml->error;
  }
  $accounts = array();
  $k = 0;
  foreach($results_xml->accounts->account as $account){

      array_push($accounts, $account);

  }
  return $results_xml;
}

  echo json_encode(getTheAccounts());

/*
// Loop through accounts, see if there is a user for each account.
// Display accounts with no user.
// If account has user, check that user has engagemoreid that agrees
//      Check that accounts status is the same as the users status ?
//

$isolated_accounts = array();
$isolated_users = array();
foreach($accounts as $account){
//  if(  getAUser( $account->email, $users ) == -1 )
//  {
//    if( (strpos($account->email,"@") !== false) ) {
  //    showAccount( $account );
			array_push($isolated_accounts,$account);
//    }
//  }
}
//$result["data"] = $isolated_accounts;
	$result = $isolated_accounts;
//	header("Content-type: application/json");
//	header("Cache-Control: no-cache, must-revalidate");

echo json_encode($result);
*/
?>
