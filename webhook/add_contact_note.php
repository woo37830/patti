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
  require_once '../webhook/add_contact.php';
  require_once '../webhook/get_contact.php';
  require_once '../webhook/mysql_common.php';
  require_once '../webhook/utilities.php';

  $today = date("D M j G:i:s T Y");

  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'AddContactNote.aspx';

  $names = firstAndLastFromEmail($from);
  $first_name = $names[0];
  $last_name = $names[1];
  $from_email_address = $names[2];
  //echo "from_email_address: $from_email_address\n";
  $names = firstAndLastFromEmail($to);
  $first_name = $names[0];
  $last_name = $names[1];
  $to_email_address = $names[2];
  //echo "to_email_address: $to_email_address\n";


  $email = "\nfrom:\t$from_email_address\n";
  $email .= "\nto:\t$to_email_address\n";
  $email .= "\nmessage-id:\t$messageId\n";
  $email .= "\nsubject:\t$subject\n";
  $email .= "\nmessage:\t$message\n";
  $email .= "\nAttachments:\t$attachmentLog\n";

  // Get the agents engagemorecrm id from the users table

  $agentId = getAccountId( $from_email_address );
  if( $agentId == -1 )
  {
    echo "FAILURE: $from_email_address does not have an engagemorecrm id\n";
    logit($from_email_address,$to, "FAILURE: $from_email_address does not have an engagemorecrm id in the users table" );
    return false;
  }
  // getContact will either return the id of an existing contact OR
  // it will create the contact and return the  new id.
  $contactId = getContact( $today, $from_email_address, $to_email_address );
  echo "\nResult of getContact of $agentId for $to_email_address is: $contactId\n";
  if( $contactId == "-1" ) // Contact does not exist in agents list
  {
      return false;
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

  if (isset($results_xml->error))
  {
    echo "\nFailure: " . $results_xml->error . "\n";
    logit($from_email_address,strip_tags($postArray), "FAILURE: $results_xml->error" );
    return false;
  }

  echo "\nSUCCESS: email added as note: $results_xml->noteid to $to_email_address, contact of $agentId\n";
  logit($from_email_address,strip_tags($postArray), "SUCCESS: email added as noteid $results_xml->noteid to contact $to_email_address" );
  return true;

}

?>
