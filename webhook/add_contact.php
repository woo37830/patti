<?php
function createContact($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray)
{
  require '../webhook/config.ini.php';
//  require '../webhook/thrivecart_api.php';

//  require '../webhook/mysql_common.php';
//  require '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'AddContact.aspx';

  $agentId = getAccountId( $from );
  if( $agentId == -1 )
  {
    echo "\nFAILURE: $from does not have an engagemorecrm id<br />\n";
    logit($from,$postArray, "FAILURE: $from does not have an engagemorecrm id" );
    exit;
  }
  echo "\nGot agentId = $agentId on lookup of $from\n";

  // Parse out first and last name if present
  $first_name = "John";
  $last_name = "Gomez";
  $email_address = $to;

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'email' => $to,
  	'teammemberid' => $agentId,
    'firstname' => $first_name,
    'lastname' => $last_name
  );
  $results_xml = thrivecart_api($url, $data); // returns simplexml_load_string object representation
  	echo "\n<br />results_xml: " . $results_xml . "<br />\n";

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
    logit($from,$postArray, "FAILURE: $results_xml->error" );
    return false;
  }
  return true;

}
?>
