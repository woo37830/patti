<?php
######################################################
##
##	HTTP Post Sample Processing Code
##	Van Stokes
##	04.08.2014
##	v0.05
##
##	This script processes email data sent over HTTP POST
##	from mailnuggets.com.
##
##	To use this script:
##	- Set it up on your server at an address like
##	  http://yourserver.com/postTest.php
##
##	- Make sure that you have created a mailnuggets rule
##	  that posts to http://yourserver.com/postTest.php
##
##	- Each mailnuggets rule allows you to specify the posted
##	  email format as: JSON, Key-Value pairs or the raw
##	  email.  **JSON or Key-Value pairs is the recommended format.**
##
##	NOTES:
##	- This script requires the json_decode() function
##	  included on PHP versions 5.2 onward
##
##	- This make sure that the parent directory is writable
##	  for saving attachments and logging.
##
######################################################

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../webhook/add_contact_note.php';
require_once '../webhook/mysql_common.php';
require_once '../webhook/Contact.php';
// Set to 1 to Save Attachements

// NOTE: By default, this saves attachments to the same directory as this script.
// it's recommended to set this to 0 when not testing the script, or change the directory where
// attachments are saved.  If someone emails you maliciousFile.php and can access the file, it is bad.

$saveAttachments = 1;
$attachmentLog = "log";
// Set to TRUE for testing
$useTestEmail = FALSE;
$error = 0;

######################################################
##
## 	JSON FORMATTED EMAIL
## 	- The JSON encoded email below can be used for testing.
##
######################################################

$testEmail = '{"headers":{
	"from jwooten37830@icloud.com  sun aug  7 14":"26:40 2022",
	"return-path":"<jwooten37830@icloud.com>",
	"x-original-to":"mail@leads.woo37830.mailnuggets.com",
	"delivered-to":"mail@leads.woo37830.mailnuggets.com",
	"subject":"Test9",
	"message-id":"<B58E7329-71D8-4628-B994-E3FE018AAFC5@icloud.com>",
	"date":"Sun, 7 Aug 2022 10:26:38 -0400",
	"to":"mail@leads.woo37830.mailnuggets.com"},
	"parts":[
		{
			"headers":{
				"content-transfer-encoding":"quoted-printable",
				"content-type":"text\/plain; charset=us-ascii"
			},
			"ctype_primary":"text",
			"ctype_secondary":"plain",
			"ctype_parameters":{"charset":"us-ascii"},
			"body":"Test that (800) 300-4774 for ralph.jones@gmail.com John Wooten \n\n\n"
		}
	]
}';



$data = '{"Coords":[{"Accuracy":"65","Latitude":"53.277720488429026","Longitude":"-9.012038778269686","Timestamp":"Fri Jul 05 2013 11:59:34 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.277720488429026","Longitude":"-9.012038778269686","Timestamp":"Fri Jul 05 2013 11:59:34 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.27770755361785","Longitude":"-9.011979642121824","Timestamp":"Fri Jul 05 2013 12:02:09 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.27769091555766","Longitude":"-9.012051410095722","Timestamp":"Fri Jul 05 2013 12:02:17 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.27769091555766","Longitude":"-9.012051410095722","Timestamp":"Fri Jul 05 2013 12:02:17 GMT+0100 (IST)"}]}';

$time = time();
$today = date("D M j G:i:s T Y");

$myFile = "lead-handler-$time.txt";
$fh = fopen($myFile, 'w') or die("can't open file");

/**
 * Remove the first and last quote from a quoted string of text
 *
 * @param mixed $text
 */
function stripQuotes($text) {
  return preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $text);
}

// it's easier to access the data when converted to an array
function objectToArray( $object )
 	{
     	if( !is_object( $object ) && !is_array( $object ) )
		    {
					echo "return non-object";
		         return $object;
		    }

		if( is_object( $object ) )
		    {
		         $object = get_object_vars( $object );
						 echo "return object";
		    }
				echo "return array_map of objectToArray of object";
     	return array_map( 'objectToArray', $object );
	}


function decodeQuotedPrintable ($message)
	{
		// Remove soft line breaks
        $message = preg_replace("/=\r?\n/", '', $message);

        // Replace encoded characters
		$message = preg_replace('/=([a-f0-9]{2})/ie', "chr(hexdec('\\1'))", $message);

		return $message;
	}

	// Define recursive function to extract nested values
	function printValues($arr) {
	    global $count;
	    global $values;

	    // Check input is an array
	    if(!is_array($arr)){
				echo "Type of arr is: ".getType($arr)."\n";
	        die("ERROR: Input is not an array");
	    }

	    /*
	    Loop through array, if value is itself an array recursively call the
	    function else add the value found to the output items array,
	    and increment counter by 1 for each value found
	    */
	    foreach($arr as $key=>$value){
	        if(is_array($value)){
	            printValues($value);
	        } else{
	            $values[] = $value;
	            $count++;
	        }
	    }

	    // Return total count and values found in array
	    return array('total' => $count, 'values' => $values);
	}
// get key variables from $emailArray object if no errors

$email = "\nProcessed on: $today";
fwrite($fh, $email);
echo "\nPost: ".count($_POST)."\n";

if( count($_POST) == 0 ) {
	fwrite($fh, "\nNo data in POST\nGoing to test mode");
//	fclose($fh);
//	die("\nNo data in POST\n");
	$useTestEmail = TRUE;
	$_POST['body'] = "ACCOUNT=||jwooten37830@icloud.com||\n Test that (800) 300-4774 for piggly.wiggle@gmail.com Piggly Wiggly 200 North Main Street, Oak Ridge TN 37830 \n\n\n";
	$_POST['to'] = "wooten.666@gmail.com";
	$_POST['from'] = "jwooten37830@me.com";
	$_POST['message-id'] = "123";
	$_POST['date'] = "20220902";
	$_POST['subject'] = "TEST-LEAD";
	$_POST['delivered-to'] = "me";
	$_POST['return-path'] = "jwooten37830@icloud.com";
}

try {
if($error != 1)
	{
		$message = $_POST['body'];
		fwrite($fh, "\nSTATUS 171: $message\n");
		$to = $_POST['to'];
		$from = $_POST['from'];
		$messageId = $_POST['message-id'];
		$date = $_POST['date'];
		$subject = $_POST['subject'];
		$reply_to = $_POST['reply-to'];

//		echo "return-path: ".$emailArray['headers']['return-path']."\nto: ".$_POST['to']."\nmessage: ".$emailArray['parts']['body'];
		// This may be an array
		$deliveredTo = $_POST['delivered-to'];

		$returnPath = $_POST['return-path'];
		if( strpos($returnPath, '<') === 0 ) {
			$returnPath = substr($returnPath, 1, -1);
		}

//		$replyTo = $emailArray['headers']['reply-to'];
//		$contentType = $emailArray['headers']['content-type'];
//		$contentTransferEncoding = $emailArray['headers']['transfer-encoding'];

		// if the key-value posting option is used get that data


		// $message and $messagehtml are set above
		// if only an html version exists both $message and $messagehtml with be the same
}



######################################################
## 	log the processed email to postTestLog.txt
##	(Make sure directory and/or file is writtable)
######################################################
$added = "Starting";

//$logEmailArray = print_r($emailArray, 1);
//$postArray = print_r($_POST, 1);

//$email .= "\n------------\n\$_POST array:\n------------\n$postArray";

$email .= "\n****************\n";
$email .= "Decoded values: ";
$email .= "\n****************";
//$email .= "\n------------\n\$_POST['email'] value (JSON only):\n------------\n$postedEmail";
//$email .= "\n------------\nEmail object (JSON only):\n------------\n$logEmailArray\n";
$email .= "\n------------\nfrom:\n------------\n$from\n";
$email .= "\n------------\nto:\n------------\n$to\n";
$email .= "\n------------\ndelivered-to:\n------------\n$deliveredTo\n";
$email .= "\n------------\nreturn-path:\n------------\n$returnPath\n";
$email .= "\n------------\nmessage-id:\n------------\n$messageId\n";
$email .= "\n------------\nreply-to:\n--------------\n$reply_to\n";
//$email .= "\n------------\ncontent-type:\n------------\n$contentType\n";
//$email .= "\n------------\ncontent-transfer-encoding:\n------------\n$contentTransferEncoding\n";
//$email .= "\n------------\nsubject:\n------------\n$subject\n";
$email .= "\n------------\nmessage:\n------------\n$message\n";
//$email .= "\n------------\nmessagehtml:\n------------\n$messagehtml\n";
//$email .= "\n------------\nAttachments:\n------------\n$attachmentLog\n";


$email .= "Email post log:\n$email \n";
try {
	if( $error !== 1 )
	{
		$contact = new Contact($reply_to);
		$contact->set_source($from);

		$email .= "\nContact instance created for ".$contact."\n";
	//	echo "\n---------------------Contact-----------------\n";
	//	echo $contact;
	//	echo "\n----------------------------------------------\n";
	$added = "Not activated";

			try {
//				echo "\n$email";
				$added = addContactNote($today, $contact->get_acct(), $contact->get_email(), $messageId, "Test", "\n------\n".$contact."\n-------------\n$message\n", "", "");
				$email .= "Contact ".$contact->get_email()." created for ".$contact->get_acct()." at $added \n";
				echo "\nContact ".$contact->get_email()." created for ".$contact->get_acct()." at $added \n";
			}
			catch (exception $e) {
				$email .= "#Posted $today, Exception $e occurred attempting to add ".$contact->get_email()." for ".$contact->get_acct()."\n";
				echo "\n#Posted $today, Exception $e occurred attempting to add ".$contact->get_email()." for ".$contact->get_acct()."\n";
			}
	}
} catch( exception $e1) {
	$email .= "An exception $e1 was thrown!";
	echo "\nException $e1 was thrown";
}
} catch( Exception $e5) {
	$email .= "\nAn exception $e5 was thrown\n";
}
fwrite($fh, $email);
fclose($fh);

// return a confirmation to mailnuggets
//echo "\n#Posted $today#, $log";
echo "All Done!\n";



?>
