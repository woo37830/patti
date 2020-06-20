<?php
function addContact($today, $from, $to)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'AddContact.aspx';
  $names = firstAndLastFromEmail($from);
  $first_name = $names[0];
  $last_name = $names[1];
  $email_address = $names[2];

  $agentId = getAccountId( $email_address );
  if( $agentId == -1 )
  {
    echo "FAILURE: $from does not have an engagemorecrm id\n";
    logit($email_address,$first_name, "FAILURE: $email_address does not have an engagemorecrm id" );
    exit;
  }
  //echo "\nGot agentId = $agentId on lookup of $from in add_contact.php\n";

  // Parse out first and last name if present
  $str = $to;
  $names = firstAndLastFromEmail($to);
  $first_name = $names[0];
  $last_name = $names[1];
  $email_address = $names[2];

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'email' => $email_address,
  	'accountid' => $agentId,
    'firstname' => $first_name,
    'lastname' => $last_name
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
    logit($from,$results_xml, "FAILURE: $results_xml->error" );
    return "-1";
  }
  //echo "\nresults_xml: " . $results_xml . "\n";
  logit($from, $email_address, "SUCCESS: contact  added with $results_xml->contactid");
  return $results_xml->contactid;

}
?>
