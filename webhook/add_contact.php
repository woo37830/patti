<?php

function addContactData($data)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';
  //$api_endpoint = 'https://www.allclients.com/api/2/';
  $url = $api_endpoint . 'AddContact.aspx';

  $results_xml = thrivecart_api($url, $data); // returns simplexml_load_string object representation
  //echo "\nadd_contact: got results_xml";
  if( !isset($results_xml) )
  {
    die( "\nresults_xml is not set!" );
  }
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
//    echo "\nFailure: results_xml->error !\n";
//    echo json_encode($data);
    // json_encode($results_xml)
    logit($data->email,json_encode($results_xml), "FAILURE: add_contact.php $data->email $results_xml->error" );
    return "-1";
  }
  //echo "\nresults_xml: " . $results_xml . "\n";
  //logit($email_address, $email_address, "SUCCESS: contact  added with $results_xml->contactid");
  return $results_xml;

}
// agentId must be an integer and is the acct id of the agent
function addContact($today, $agentId, $firstName, $lastName, $email, $phone, $source)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];

  $phone =  str_replace('(', '', $phone);
  $phone = str_replace(')','', $phone);
  $phone = str_replace('-', '', $phone);
  $phone = str_replace(' ','', $phone);

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'email' => $email,
    'phone' => $phone,
  	'accountid' => (int)$agentId,
    'firstname' => $firstName,
    'lastname' => $lastName,
    'source'   => $source
  );
  return addContactData($data);
}

function addContactInstance($contact)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_key      = 'C06EA0D3408C10928D47C8D96F9F8CC4';

  $data = array(
    'accountid' => (int)$contact->get_acctNumber(),
  	'apikey'    => $api_key,
    'firstname' => $contact->get_firstName(),
    'lastname' => $contact->get_lastName(),
    'address' => $contact->get_addr(),
//    'city' => $contact->get_city(),
//    'state' => $contact->get_state(),
//    'postalcode' => $contact->get_zip(),
    'phone1' => $contact->get_phone(),
//    'source'   => $contact->get_source(),
//    'addednote' => $contact->get_source(),
//    'memo' => $contact->get_inputStr(),
    'memo' => "addContactInstance",
    'email' => $contact->get_email()
  );
//  foreach(array_keys($data) as $key){
//    echo $key."->".$data[$key]."\n";
//}

  return addContactData($data);
}

?>
