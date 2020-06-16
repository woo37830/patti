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
require 'config.ini.php';
require 'mysql_common.php';
require 'utilities.php';

/**
 * AllClients Account ID and API Key.
 */
$account_id   = $config['MSG_USER'];
$api_key      = $config['MSG_PASSWORD'];
$api_endpoint = 'https://secure.engagemorecrm.com/api/2/';


function addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog)
{
  $email_data = "$today,$from,$to,$messageId,$subject,$message,$attachmentLog#"";
  logit($email,$email_data,"Add Contact Note");
  return true;
}

?>
