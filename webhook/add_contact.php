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
   //  echo "\nFailure: " . $results_xml->error . "\n";
   //  logit($from_email_address,$results_xml, "FAILURE: $results_xml->error" );
     $string = "\nFAILURE1: ".$results_xml->error;
     return $string;
   }
   if( !$results_xml )
   {
     return "-1";
   }
   print_r($results_xml);
//   if( !isset($results_xml->contacts->contact->id) )
//   {
 //      echo "\nHaving to add contact $to_email_address to $from_email_address\n";
 //      $contactId = addContact($today, $agentId, $firstName, $lastName, $email, $source);
 //      $contactId = addContact($today, $from, "First", "Last", $to_email_address, "email capture");
 //      echo "\nGot a contactId of $contactId adding $from_email_address to $to_email_address\n";
 //      return $contactId;
//         return "-1";
//   }
   $contactId = $results_xml->contactid;
 //  echo "\nContact $to_email_address of $agentId exists and has id of $contactId\n";
   return $contactId;


}
// agentId must be an integer and is the acct id of the agent
function addContact($agentId, $firstName, $lastName, $email, $phone, $memo, $street, $city, $state, $zip, $source)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';
  require_once '../webhook/get_contact.php';
  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];

  if( strpos($agentId, '@') != false )
  { // We have the email of an agent instead of the agents account id
//      echo "agentId = $agentId";
      $user = getUserByEmail($agentId);
      $agentId = $user["engagemoreid"];
  }
  if( getContact($agentId, $email) != -1 )
  {
    return "Contact $email of $agentId already exists";
  }

  $phone =  str_replace('(', '', $phone);
  $phone = str_replace(')','', $phone);
  $phone = str_replace('-', '', $phone);
  $phone = str_replace(' ','', $phone);

  $today = date("D M j G:i:s T Y");

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'email' => $email,
    'phone1' => $phone,
    'address' => $street,
    'city' => $city,
    'state' => $state,
    'postalCode' => $zip,
  	'accountid' => (int)$agentId,
    'firstname' => $firstName,
    'lastname' => $lastName,
    'memo' => $memo,
    'addednote' => 'Contact added using add_contact.php',
    'adddate' => $today,
    'source'   => $source
  );
//  print_r($data);
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
//  $api_key      = 'C06EA0D3408C10928D47C8D96F9F8CC4';

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
