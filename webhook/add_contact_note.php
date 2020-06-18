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
  require '../webhook/thrivecart_api.php';
  require '../webhook/add_contact.php';
  require '../webhook/mysql_common.php';
  require '../webhook/utilities.php';
  $account_id   = $config['MSG_USER'];
  $api_key      = $config['MSG_PASSWORD'];
  $api_endpoint = 'https://secure.engagemorecrm.com/api/2/';

  $url = $api_endpoint . 'AddContactNote.aspx';

  $names = firstAndLastFromEmail($from);
  $first_name = $names[0];
  $last_name = $names[1];
  $from_email_address = $names[2];

  $names = firstAndLastFromEmail($to);
  $first_name = $names[0];
  $last_name = $names[1];
  $to_email_address = $names[2];


  $email = "\n------------\nfrom:\n------------\n$from_email_address\n";
  $email .= "\n------------\nto:\n------------\n$to_email_address\n";
  $email .= "\n------------\nmessage-id:\n------------\n$messageId\n";
  $email .= "\n------------\nsubject:\n------------\n$subject\n";
  $email .= "\n------------\nmessage:\n------------\n$message\n";
  $email .= "\n------------\nAttachments:\n------------\n$attachmentLog\n";

  // Get the agents engagemorecrm id from the users table
  $names = firstAndLastFromEmail($from);
  $first_name = $names[0];
  $last_name = $names[1];
  $email_address = $names[2];

  $agentId = getAccountId( $email_address );
  if( $agentId == -1 )
  {
    echo "\nFAILURE: $from does not have an engagemorecrm id<br />\n";
    logit($from,$postArray, "FAILURE: $from does not have an engagemorecrm id" );
    exit;
  }
  echo "\nGot agentId = $agentId on lookup of $from\n";
  $names = firstAndLastFromEmail($to);
  $first_name = $names[0];
  $last_name = $names[1];
  $email_address = $names[2];

  // See if we have the contacts emgagemorecrm id in the users table
  $contactId = getAccountId( $email_address );
  $identifyMethod = 1;
  $identifyValue = $contactId;
  if( $contactId === -1 )
  {
    echo "\nPROBLEM: $to does not have an engagemorecrm id<br />\n";
    logit($to,$postArray, "PROBLEM: $to does not have an engagemorecrm id" );
    $identifyMethod = 2;
    $identifyValue = $email_address;
  }
  echo "\n$to appears to have contactId of $contactId, using method $identifyMethod with $identifyValue\n";
  //   	'teammemberid' => $agentId,
//  TODO: Check to see if the contact exists using QuickSearchContacts.aspx,
// Supply apiusername, apipassword, accountid, and searchstring = "email = $email_address";
// If it does exist, use the method 1 and the contactid else
// create the contact, get the contact id, then add the contactNote to it.
//
  $data = array(
  	'apiusername' => $account_id,
  	'apipassword'    => $api_key,
    'accountid' => $agentId,
  	'identifymethod'  => $identifyMethod,
    'identifyvalue' => $identifyValue,
    'note' => $email
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

  if (isset($results_xml->error))
  {
    echo "\nFailure: " . $results_xml->error . "<br />\n";
    logit($from,$postArray, "FAILURE: $results_xml->error" );
    $res = strpos($results_xml->error, "Contact not found");
    echo "\nres = $res\n";
    if( $res )
    {
      echo "\nFailure: $from does not have a contact $to" . "<br />\n";
      // Add the person and note as a new contact and note
      $res = createContact($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
      if( $res === false )
      {
        echo "\nFailure: Could not create a contact $to for $from" . "<br />\n";
        logit($from,$postArray, "FAILURE: $results_xml->error" );
        return false;
      }
      echo "\nSuccess: Created a contact with id: $results_xml->contactid for $to" . "<br />\n";
      $data = array(
        'apiusername' => $account_id,
        'apipassword'    => $api_key,
        'accountid' => $agentId,
        'identifymethod'  => $identifyMethod,
        'identifyvalue' => $identifyValue,
        'note' => $email
      );
      $results_xml = thrivecart_api($url, $data); // returns simplexml_load_string object representation

      if (!isset($results_xml->error))
      {
        echo "\nSuccess: After adding contact for $to, email was added as note: $results_xml->noteid\n";
        return true;
      }
      echo "\nFailure: " . $results_xml->error . "<br />\n";
      logit($from,$postArray, "FAILURE: $results_xml->error" );
      return false;
    }
    return false;
  }

  echo "\nSUCCESS: email added as note: $results_xml->noteid to $to<br />\n";
  logit($from,$postArray, "SUCCESS: email added as note $results_xml->noteid to $to" );
  return true;

}

?>
