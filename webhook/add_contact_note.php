<?php
######################################################
##
##	Add Contact Note
##	John Wooten, Ph.D
##	06.16.2020
##	v0.01
##
##	This script takes data from pastTest.php that contains the
##  email data received via a bcc email address pointing to a
##  mailnuggets email account url. The email data is used to determine
##  the engagemorecrm accountId of the sender and the receiver and use that
##  to add a contact note containing the email to the receivers notes
##  in the engagemorecrm system.
##  If the receiver is not a contact of the sender, then the receivers email
##  address is added to the senders list of contacts and the email is added as
##  contact note to that new contact.
##
##	To use this script, the postTest.php should be
##	- Set it up on your server at an address like
##	  http://yourserver.com/postTest.php
##
##	- Make sure that you have created a mailnuggets rule
##	  that posts to http://yourserver.com/postTest.php
##
##	- Each mailnuggets rule allows you to specify the posted
##	  email format as: JSON, Key-Value pairs or the raw
##	  email.  **JSON is the recommended format.**
##
##	NOTES:
##	- This script requires the json_decode() function
##	  included on PHP versions 5.2 onward
##
##	- This make sure that the parent directory is writable
##	  for saving attachments and logging.
##
##  - This also requires that the MySQL database users_db with tables
##    users and logs be available.  They are used via the mysql_common.php
##    functions and the parameters provided in config.inc.php
######################################################

/**
 * AllClients Account ID and API Key.
 */


function addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray)
{
  require '../webhook/config.ini.php';
  require_once '../webhook/thrivecart_api.php';
  require_once '../webhook/add_contactFromEmail.php';
  require_once '../webhook/get_contact.php';
  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';

  $today = date("D M j G:i:s T Y");

  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'AddContactNote.aspx';

  $names = firstAndLastFromEmail($from);
  $size = sizeof($names);
  //echo "from_email_address: $from_email_address\n";
//  logit($from, "$to, sizeof names = $size", "LOG: (add_contact_note)-67");

  if ( sizeof( $names) < 3 )
  {
    $first_name = $names[0];
    $last_name = "";
    $from_email_address = $names[1];

  } else {
    $first_name = $names[0];
    $last_name = $names[1];
    $from_email_address = $names[2];
  }

  $names = firstAndLastFromEmail($to);
 // logit($from, "$to, sizeof names = $size", "LOG: (add_contact_note)-82");

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

  if( $to_email_address == null ) {
  //  logit($from_email_address, "from email provided a null to_email_address", "FAILURE: (add_contact_note)");
    return false;
  }
  //echo "to_email_address: $to_email_address\n";


  $email = "\nfrom:\t$from_email_address\n";
  $email .= "\nto:\t$to_email_address\n";
  $email .= "\nmessage-id:\t$messageId\n";
  $email .= "\nsubject:\t$subject\n";
  $email .= "\nmessage:\t$message\n";
  $email .= "\nAttachments:\t$attachmentLog\n";
//  logit($from, "email = $email", "LOG: (add_contact_note)-109");

  // Get the agents engagemorecrm id from the users table
try {
  try {
  $agentId = getAccountId( $from_email_address );
  if( $agentId == -1 )
  {
    echo "FAILURE: $from_email_address does not have an engagemorecrm id\n";
    die("\n$from_email_address does not have an account\n");
//    logit($from_email_address,$to, "FAILURE: $from_email_address does not have an engagemorecrm id in the users table" );
    return false;
  }
  //echo "AgentId: $agentId";
} catch (Exception $e1) {
  return "FAILURE: Exception $e1 in getAccountId add contact note";

}
  // getContact will either return the id of an existing contact
  try {
  $contactId = getContact( $today, $from_email_address, $to_email_address );
  echo "\nResult of getContact of $from_email_address for $to_email_address is: $contactId\n";
//  die("\nAccount: $agentId has a contactId of $contactId\n");
  if( $contactId == "-1" ) // Contact does not exist in agents list
  {
      $source = $subject;  // for now
//      die( "Will try to add $to_email_address as a contact of $from_email_address\n");
      $contactId = addContactFromEmail($today, $agentId, $to_email_address, $source); // Use full to get first and last
      if( intval($contactId) == -1  )
      {
        echo "Failure adding contact $to_email_address to $from_email_address account - $contactId";
//        logit($from_email_address, strip_tags($postArray), "FAILURE: Attempt to add contact $to_email_address contactId = $contactId");
        return false;
      }
//      logit($from_email_address,strip_tags($postArray), "add_contact_note $to_email_address for account $from_email_address for source $source" );

      //echo "Added $to_email_address to $from_email_address as contactId: $contactId\n";
  }
} catch (Exception $e2) {
  return "FAILURE: Exception $e2 in getAccountId getContact";
}

  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'accountid' => $agentId,
  	'identifymethod'  => 2,
    'identifyvalue' => $to_email_address,
    'note' => strip_tags($email)
  );
  $results_xml = thrivecart_api($url, $data); // returns simplexml_load_string object representation
  //echo "Result of addContactNote is: $results_xml\n";
  /**
   * If an API error has occurred, the results object will contain a child 'error'
   * SimpleXMLElement parsed from the error response:
   *
   *   <?xml version="1.0"?>
   *   <results>
   *     <error>Authentication failed</error>
   *   </results>
   */

  if (isset($results_xml->error) )
  {
//    logit($from_email_address,strip_tags($postArray), "FAILURE: (add_contact_note) $results_xml->error" );
    return "FAILURE: (add_contact_note) $results_xml->error" ;
  }

//  echo "\nSUCCESS: email added as note: $results_xml->noteid to $to_email_address, contact of $agentId\n";
//  logit($from_email_address,strip_tags($postArray), "SUCCESS: email added as noteid $results_xml->noteid for $to_email_address to contact $to_email_address" );
  return "SUCCESS: email added as noteid $results_xml->noteid for $to_email_address to contact $to_email_address";

}

catch( exception $e )
{
//  logit($from_email_address,strip_tags($postArray), "FAILURE: Exception $e");
  return "FAILURE: Exception $e";
}
}

?>
