<?php
function addContact($today, $agentId, $firstName, $lastName, $email, $source)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'AddContact.aspx';


  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'email' => $email,
  	'accountid' => $agentId,
    'firstname' => $firstName,
    'lastname' => $lastName,
    'source'   => $source
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
    echo "\nFailure: " . $results_xml->error . "\n";
    logit($email,json_encode($results_xml), "FAILURE: add_contact.php $results_xml->error" );
    return "-1";
  }
  //echo "\nresults_xml: " . $results_xml . "\n";
  //logit($email_address, $email_address, "SUCCESS: contact  added with $results_xml->contactid");
  return $results_xml;

}
?>
