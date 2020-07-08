<?php
function getAccounts($today, $email)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'GetAccounts.aspx';


  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key
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

  if (isset($results_xml->error)) {
  //  echo "\nresults_xml: " . $results_xml . "\n";
    echo "\nFailure: " . $results_xml->error . "\n";
    logit($email,$results_xml->error, "FAILURE: get_accounts.php: $results_xml->error" );
    die ("FAILURE: $results_xml->error");
  }
//  var_dump($results_xml);
  $accounts = array();
  foreach($results_xml->accounts as $account){
      array_push($accounts, $account);
  }
  return $accounts;

}
?>
