<?php

function getAccounts() {
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

?>
