<?php
function getContact($agentId, $contact_email)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/add_contact.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'QuickSearchContacts.aspx';

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
  //  echo "\nresults_xml: " . $results_xml . "\n";
  //  echo "\nFailure: " . $results_xml->error . "\n";
    logit($from_email_address,$results_xml, "FAILURE: $results_xml->error" );
    return -1;
  }
  if( !isset($results_xml->contacts->contact) )
  {
//      echo "\nHaving to add contact $to_email_address to $from_email_address\n";
//      $contactId = addContact($today, $agentId, $firstName, $lastName, $email, $source);
//      $contactId = addContact($today, $from, "First", "Last", $to_email_address, "email capture");
//      echo "\nGot a contactId of $contactId adding $from_email_address to $to_email_address\n";
//      return $contactId;
        return -1;
  }
  $contactId = $results_xml->contacts->contact->id;
//  echo "\nContact $to_email_address of $agentId exists and has id of $contactId\n";
  return $contactId;

}
?>
