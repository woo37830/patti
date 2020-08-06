<?php
function getAllAccounts()
{
  require 'config.ini.php';
  require 'thrivecart_api.php';

  require 'mysql_common.php';
  require 'utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'GetAccounts.aspx';

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'showpendingdelete' => 1
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
    echo "\nFailure: " . $results_xml->error . "<br />\n";
    return $results_xml->error;
  }
  $accounts = array();
  foreach($results_xml->accounts->account as $account){
    array_push($accounts, $account);
  }

  return $accounts;

}
?>
