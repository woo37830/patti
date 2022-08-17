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
require_once 'Prospect.php';
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

/**
 * Remove the first and last quote from a quoted string of text
 *
 * @param mixed $text
 */
function stripQuotes($text) {
  return preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $text);
}

echo "\nPost: ".count($_POST)."\n";
if( count($_POST) > 0 )
{
	try {
		$json = file_get_contents('php://input');
		echo "\njson: ".$json."\n";
	} catch( Exception $e2) {
		die( "Exception $e2 on get_contents\n");
	}
	// decode the json data
	$data = json_decode($json);
	echo "\ndata: ".$data."\n";
		if( get_magic_quotes_gpc() == 1)
			{
				$postedEmail = stripslashes($data['email']);
			}
		else
			{
				$postedEmail = $data['email'];
			}
			$postedEmail = stripQuotes($postedEmail);
}
else
{
		$postedEmail = stripQuotes($testEmail);
		echo "Using testEmail\n";
}

$encoding = mb_detect_encoding($postedEmail);

if($encoding == 'UTF-8') {
  $postedEmail = preg_replace('/[^(\x20-\x7F)]*/','', $postedEmail);
}

if(!(function_exists('json_decode')))
	{
		echo " ERROR: Missing json_decode function. ";
	}


if(function_exists('json_decode') && $postedEmail != null)
	{
		$emailObject = json_decode($postedEmail, TRUE);
		if( $emailObject == null )
		{
			die("ERROR at 99: emailObject is null, postedEmail is:\n*".$postedEmail."*\n");
		}

		if(!($emailObject == json_decode($postedEmail, TRUE)))
			{
				// the string is not valid JSON
				echo " ERROR 132: Not valid JSON. ";
			}
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
if($error != 1)
	{
		// convert object to an array recursively
//		  echo "postedEmail is: ".getType($postedEmail)."\n of length ".strlen($postedEmail)."\n";

			$emailObject = json_decode($postedEmail, true);
			if( $emailObject == null )
			{
				die("ERROR: emailObject is null, postedEmail is:\n*".$postedEmail."*\n");
			}
//			echo "emailObject is: ".getType($emailObject)."\n";

//		print_r($emailObject);
		$emailArray = $emailObject;
//		$subject = $emailArray['headers']['subject'];

		if($emailArray['parts'][0]['body'] != null)
			{
				$message = $emailArray['parts'][0]['body'];
//				echo "STATUS 213: $message\n";
			}
			else
			{
				die("ERROR: email array does not contain a body");

			}
		// if messages comes as both plain and html parts (multipart) treat the plain one as the main one


		// look for the message in the event something is attached, since array will be different


		// the email could be HTML only, or HTML only with attachment


			// Find out if the email has attachments
//		$from = $emailArray['headers']['from'];
		$to = $emailArray['headers']['to'];
		$messageId = $emailArray['headers']['message-id'];

//		echo "return-path: ".$emailArray['headers']['return-path']."\nto: ".$_POST['to']."\nmessage: ".$emailArray['parts']['body'];
		// This may be an array
		$deliveredTo = $emailArray['headers']['delivered-to'];

		$returnPath = $emailArray['headers']['return-path'];
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
$time = time();
//$myFile = "lead-handler-$time.txt";
//$fh = fopen($myFile, 'w') or die("can't open file");

$today = date("D M j G:i:s T Y");
$logEmailArray = print_r($emailArray, 1);
$postArray = print_r($_POST, 1);

$email = "\nProcessed on: $today";
$email .= "\n------------\n\$_POST array:\n------------\n$postArray";

$email .= "\n****************\n";
$email .= "Decoded values: ";
$email .= "\n****************";
$email .= "\n------------\n\$_POST['email'] value (JSON only):\n------------\n$postedEmail";
$email .= "\n------------\nEmail object (JSON only):\n------------\n$logEmailArray\n";
//$email .= "\n------------\nfrom:\n------------\n$from\n";
$email .= "\n------------\nto:\n------------\n$to\n";
$email .= "\n------------\ndelivered-to:\n------------\n$deliveredTo\n";
$email .= "\n------------\nreturn-path:\n------------\n$returnPath\n";
$email .= "\n------------\nmessage-id:\n------------\n$messageId\n";
//$email .= "\n------------\ncontent-type:\n------------\n$contentType\n";
//$email .= "\n------------\ncontent-transfer-encoding:\n------------\n$contentTransferEncoding\n";
//$email .= "\n------------\nsubject:\n------------\n$subject\n";
$email .= "\n------------\nmessage:\n------------\n$message\n";
//$email .= "\n------------\nmessagehtml:\n------------\n$messagehtml\n";
//$email .= "\n------------\nAttachments:\n------------\n$attachmentLog\n";


echo "Email error=$error\n";
$log = "Email post log:\n$email \n";
try {
	if( $error !== 1 )
	{
		$prospect = new Prospect($returnPath, $message);
	//	echo "\n---------------------Prospect-----------------\n";
	//	echo $prospect;
	//	echo "\n----------------------------------------------\n";
	$added = "Not activated";

			try {
				$added = addContactNote($today, $returnPath, $prospect->get_email(), $messageId, "Test", "\n------\n".$prospect."\n---------\n", "", "");
				$log .= "Prospect ".$prospect->get_email()." created for $returnPath at $added \n";
				echo "\nProspect ".$prospect->get_email()." created for $returnPath at $added \n";
			}
			catch (exception $e) {
				$log .= "#Posted $today, Exception $e occurred attempting to add ".$prospect->get_email()." for $returnPath";
				echo "\n#Posted $today, Exception $e occurred attempting to add ".$prospect->get_email()." for $returnPath\n";
			}
	}
} catch( exception $e1) {
	$log .= "An exception $e1 was thrown!";
	echo "\nException $e1 was thrown";
}
//fwrite($fh, $log);
//fclose($fh);

// return a confirmation to mailnuggets
//echo "\n#Posted $today#, $log";
echo "All Done!\n";



?>
