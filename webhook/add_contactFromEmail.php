<?php
function addContactFromEmail($today, $agentId, $to, $source)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';

  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';
  require_once '../webhook/add_contact.php';
  
  $names = firstAndLastFromEmail($to);
  if ( sizeof( $names) < 3 )
  {
    $first_name = $names[0];
    $last_name = "";
    $to_email_address = $names[1];

  } else {
    $first_name = $names[0];
    $last_name = $names[1];
    $to_email_address = $names[2];
  }

  // Parse out first and last name if present
  $str = $to;
  $names = firstAndLastFromEmail($to);
  $first_name = $names[0];
  $last_name = $names[1];
  $email_address = $names[2];

  try {
  $results_xml = addContact($today, $agentId, $first_name, $last_name, $email_address, $source);
} catch (Exception $e3 ) {
  echo "addContact had exceptioin $e3";
  logit($email_address, $results_xml,"FAILURE: Exception $e3");
  return "-1";
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
    echo "\nFailure: " . $results_xml->error . "\n";
    logit($email_address,$results_xml, "FAILURE: add_contactFromEmail.php $results_xml->error" );
    return "-1";
  }
  //echo "\nresults_xml: " . $results_xml . "\n";
  //logit($email_address, $email_address, "SUCCESS: contact  added with $results_xml->contactid");
  return $results_xml->contactid;

}
?>
