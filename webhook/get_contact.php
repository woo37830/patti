<?php
//
//  As of develop f243477 this works as tested by tests/testGetContact.php
//  that includes changes in webhook/thrivecart-api.php and 
function getContactData($agentId, $contact_email)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/add_contact.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'QuickSearchContacts.aspx';

  if( strpos($agentId, '@') != false )
  { // We have the email of an agent instead of the agents account id
//      echo "agentId = $agentId";
      $user = getUserByEmail($agentId);
      $agentId = $user["engagemoreid"];
  }

  //echo "\nGot to_email_address = '$to_email_address' from $to\n";

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
  	'accountid' => (int)$agentId,
    'searchstring' => $contact_email
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
    $string = "\nFAILURE1: ".$results_xml->error;
    return $string;
  }
  if( !$results_xml )
  {
    return "-1";
  }
  if( !isset($results_xml->contacts->contact->id) )
  {
     return "-1";
  }
  return $results_xml;
}
//
//  As of develop f243477 this works as tested by tests/testGetContact.php
//  that includes changes in webhook/thrivecart-api.php and 
function getContact($agentId, $contact_email)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/add_contact.php';
  require_once '../webhook/Contact.php';

  $result_xml = getContactData($agentId, $contact_email);

  if( $result_xml == "-1")
  {
    return "-1";
  }
  if( !isset($result_xml->contacts->contact->id) )
  {
     return "-2";
  }
  return $result_xml;
}
?>
