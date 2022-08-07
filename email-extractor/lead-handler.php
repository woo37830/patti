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



		$testEmail = '{"headers":{"from jwooten37830@icloud.com  sun aug  7 14":"26:40 2022","return-path":"<jwooten37830@icloud.com>","x-original-to":"mail@leads.woo37830.mailnuggets.com","delivered-to":"mail@leads.woo37830.mailnuggets.com","received":["from st43p00im-ztfb10063301.me.com (st43p00im-ztfb10063301.me.com [17.58.63.179]) by mail.mailnuggets.com (Postfix) with ESMTP id 2BD4641817 for <mail@leads.woo37830.mailnuggets.com>; Sun,  7 Aug 2022 14:26:40 +0000 (UTC)","from [10.0.0.197] (st43p00im-dlb-asmtp-mailmevip.me.com [17.42.251.41]) by st43p00im-ztfb10063301.me.com (Postfix) with ESMTPSA id 88C287001C3 for <mail@leads.woo37830.mailnuggets.com>; Sun,  7 Aug 2022 14:26:39 +0000 (UTC)"],"dkim-signature":"v=1; a=rsa-sha256; c=relaxed\/relaxed; d=icloud.com; s=1a1hai; t=1659882399; bh=VdcXYmUWZl7h4MHOj0tMy\/GditxPcxbsn676Qmw77RY=; h=From:Content-Type:Mime-Version:Subject:Message-Id:Date:To; b=02R034dtY7hZCBiKlD9fVINNn78nlelZ6XnD93oBB5IBtDfw4zL2IJgGHS5RAX1F3 tzNQIY+HJ4nP++is9FhWzbOYGQq5Ydd8rVGUd1ydla2jl1CRh6lpqsEM7I+S9HLo4Y iC8wXY8uhEolwTU9mlC0gVdL0l0AZarYdM9rWdIp4185dAznn4grzTPDfMxki2edTo wbpzpdRDoLmzfh7tEg91\/KWsyOFzlY3j1WmKM5s7kuYh0rF1GcRwsnpD1sU0Ny+Zx\/ +onj7CcvWMVdrl9uumYmLpVfcMBrr2nojdDnkyXWZuVLeZl1T+rLuqpQI8CdRixyS7 NO5t5mCuCCl1w==","from":"John Wooten <jwooten37830@icloud.com>","content-type":"multipart\/alternative; boundary=\"Apple-Mail=_E09D50C6-BF8A-4AF2-8D66-7540EACC5B06\"","mime-version":"1.0 (Mac OS X Mail 13.4 \\(3608.120.23.2.7\\))","subject":"Test9","message-id":"<B58E7329-71D8-4628-B994-E3FE018AAFC5@icloud.com>","date":"Sun, 7 Aug 2022 10:26:38 -0400","to":"mail@leads.woo37830.mailnuggets.com","x-mailer":"Apple Mail (2.3608.120.23.2.7)","x-proofpoint-guid":"1DE6kmJ46h0ZWwGF2P0WskX9wakfX0TT","x-proofpoint-orig-guid":"1DE6kmJ46h0ZWwGF2P0WskX9wakfX0TT","x-proofpoint-virus-version":"vendor=fsecure engine=1.1.170-22c6f66c430a71ce266a39bfe25bc2903e8d5c8f:6.0.138,18.0.883,17.11.64.514.0000000 definitions=2022-06-21_08:2020-02-14_02,2022-06-21_08,2022-02-23_01 signatures=0","x-proofpoint-spam-details":"rule=notspam policy=default score=0 mlxlogscore=234 mlxscore=0 clxscore=1015 suspectscore=0 bulkscore=0 adultscore=0 phishscore=0 malwarescore=0 spamscore=0 classifier=spam adjust=0 reason=mlx scancount=1 engine=8.12.0-2206140000 definitions=main-2208070078"},"ctype_primary":"multipart","ctype_secondary":"alternative","ctype_parameters":{"boundary":"Apple-Mail=_E09D50C6-BF8A-4AF2-8D66-7540EACC5B06"},"parts":[{"headers":{"content-transfer-encoding":"quoted-printable","content-type":"text\/plain; charset=us-ascii"},"ctype_primary":"text","ctype_secondary":"plain","ctype_parameters":{"charset":"us-ascii"},"body":"Test that (865) 300-4774 for wooten.666@gmail.com =\n<mailto:wooten.666@gmail.com> John Wooten 106 Crestview Lane Oak Ridge =\nTN, 37830 gets parsed.\n\n\n"},{"headers":{"content-transfer-encoding":"7bit","content-type":"text\/html; charset=us-ascii"},"ctype_primary":"text","ctype_secondary":"html","ctype_parameters":{"charset":"us-ascii"},"body":"<html><head><meta http-equiv=\"Content-Type\" content=\"text\/html; charset=us-ascii\"><\/head><body style=\"word-wrap: break-word; -webkit-nbsp-mode: space; line-break: after-white-space;\" class=\"\">Test that (865) 300-4774 for&nbsp;<a href=\"mailto:wooten.666@gmail.com\" class=\"\">wooten.666@gmail.com<\/a>&nbsp;John Wooten 106 Crestview Lane Oak Ridge TN, 37830 gets parsed.<div class=\"\"><br class=\"\"><\/div><\/body><\/html>\n"}]}';

$data = '{"Coords":[{"Accuracy":"65","Latitude":"53.277720488429026","Longitude":"-9.012038778269686","Timestamp":"Fri Jul 05 2013 11:59:34 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.277720488429026","Longitude":"-9.012038778269686","Timestamp":"Fri Jul 05 2013 11:59:34 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.27770755361785","Longitude":"-9.011979642121824","Timestamp":"Fri Jul 05 2013 12:02:09 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.27769091555766","Longitude":"-9.012051410095722","Timestamp":"Fri Jul 05 2013 12:02:17 GMT+0100 (IST)"},{"Accuracy":"65","Latitude":"53.27769091555766","Longitude":"-9.012051410095722","Timestamp":"Fri Jul 05 2013 12:02:17 GMT+0100 (IST)"}]}';
	

	if(count($_POST) < 1 && !$useTestEmail)
		{
			echo " ERROR: No data posted. ";
		}
if( count($_POST) > 1 )
{
		if( get_magic_quotes_gpc() == 1)
			{
				$postedEmail = stripslashes($_POST['email']);
			}
		else
			{
				$postedEmail = $_POST['email'];
			}
}
else
{
		$postedEmail = $testEmail;
		echo "Using testEmail";
}

if(!(function_exists('json_decode')))
	{
		echo " ERROR: Missing json_decode function. ";
	}


if(function_exists('json_decode') && $postedEmail != null)
	{
		$emailObject = json_decode($postedEmail);
		if(!($emailObject == json_decode($postedEmail)))
			{
				// the string is not valid JSON
				echo " ERROR: Not valid JSON. ";
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
		  echo "postedEmail is: ".getType($postedEmail)."\n";

			$emailObject = json_decode($postedEmail, true);
			if(!($emailObject == json_decode($postedEmail)))
				{
					// the string is not valid JSON
					die("ERROR: Not valid JSON \n\n".$postedEmail."\n");
				}

		if( $emailObject == null )
		{
			die("ERROR: emailObject is null");
		}
		echo "emailObject is: ".getType($emailObject)."\n";
		print_r($emailObject);
		$emailArray = $emailObject;
//		$subject = $emailArray['headers']['subject'];

		if($emailArray['body'] != null)
			{
				$message = $emailArray['body'];
			}
			else
			{
				die("ERROR: email array does not contain a body");

			}
		// if messages comes as both plain and html parts (multipart) treat the plain one as the main one
		if($emailArray['body'] == null && substr_count($emailArray['parts']['0']['headers']['content-type'], 'plain') > 0)
			{
				$message = $emailArray['parts']['0']['body'];

				if(substr_count($emailArray['parts']['0']['headers']['content-transfer-encoding'], 'base64') > 0)
					{
						$message = base64_decode($message);
					}

				if(substr_count($emailArray['parts']['0']['headers']['content-transfer-encoding'], 'quoted-printable') > 0)
					{
						$message = decodeQuotedPrintable($message);
					}

				// change encoding to UTF
				$charset = $emailArray['parts']['0']['ctype_parameters']['charset'];
				if(!empty($charset) && $emailArray['parts']['0']['headers']['content-transfer-encoding'] != '7bit')
					{
						$message = mb_convert_encoding($message,"UTF-8",$charset);
						$encodedsubject = mb_convert_encoding($subject,"UTF-8",$charset);
					}



			}

		if($emailArray['body'] == null && substr_count($emailArray['parts']['1']['headers']['content-type'], 'html') > 0)
			{
				$messagehtml = $emailArray['parts']['1']['body'];

				if(substr_count($emailArray['parts']['1']['headers']['content-transfer-encoding'], 'base64') > 0)
					{
						$messagehtml = base64_decode($messagehtml);
					}

				if(substr_count($emailArray['parts']['1']['headers']['content-transfer-encoding'], 'quoted-printable') > 0)
					{
						$messagehtml = decodeQuotedPrintable($messagehtml);
					}

				// change encoding to UTF
				$charset = $emailArray['parts']['1']['ctype_parameters']['charset'];
				if(!empty($charset) && $emailArray['parts']['1']['headers']['content-transfer-encoding'] != '7bit')
					{
						$messagehtml = mb_convert_encoding($messagehtml,"UTF-8",$charset);
						$encodedsubject = mb_convert_encoding($subject,"UTF-8",$charset);
					}

			}

		// look for the message in the event something is attached, since array will be different
		if($emailArray['body'] == null && substr_count($emailArray['parts']['0']['parts']['0']['headers']['content-type'], 'plain') > 0)
			{
				$message = $emailArray['parts']['0']['parts']['0']['body'];

				if(substr_count($emailArray['parts']['0']['parts']['0']['headers']['content-transfer-encoding'], 'base64') > 0)
					{
						$message = base64_decode($message);
					}

				if(substr_count($emailArray['parts']['0']['parts']['0']['headers']['content-transfer-encoding'], 'quoted-printable') > 0)
					{
						$message = decodeQuotedPrintable($message);
					}

				// change encoding to UTF
				$charset = $emailArray['parts']['0']['parts']['0']['ctype_parameters']['charset'];
				if(!empty($charset) && $emailArray['parts']['0']['parts']['0']['headers']['content-transfer-encoding'] != '7bit')
					{
						$message = mb_convert_encoding($message,"UTF-8",$charset);
						$encodedsubject = mb_convert_encoding($subject,"UTF-8",$charset);
					}

			}


		if($emailArray['body'] == null && substr_count($emailArray['parts']['0']['parts']['1']['headers']['content-type'], 'html') > 0)
			{
				$messagehtml = $emailArray['parts']['0']['parts']['1']['body'];

				if(substr_count($emailArray['parts']['0']['parts']['1']['headers']['content-transfer-encoding'], 'base64') > 0)
					{
						$messagehtml = base64_decode($messagehtml);
					}

				if(substr_count($emailArray['parts']['0']['parts']['1']['headers']['content-transfer-encoding'], 'quoted-printable') > 0)
					{
						$messagehtml = decodeQuotedPrintable($messagehtml);
					}

				// change encoding to UTF
				$charset = $emailArray['parts']['0']['parts']['1']['ctype_parameters']['charset'];
				if(!empty($charset) && $emailArray['parts']['0']['parts']['1']['headers']['content-transfer-encoding'] != '7bit')
					{
						$messagehtml = mb_convert_encoding($messagehtml,"UTF-8",$charset);
						$encodedsubject = mb_convert_encoding($subject,"UTF-8",$charset);
					}

			}

		if(!empty($encodedsubject))
			{
				$subject = $encodedsubject;
			}

		// the email could be HTML only, or HTML only with attachment
		if(substr_count($emailArray['headers']['content-type'], 'html') > 0 or substr_count($emailArray['parts']['0']['parts']['0']['headers']['content-type'], 'html') > 0)
			{
				$messagehtml = $message;

				// optional
				$message = html_entity_decode($message);
			}


			// Find out if the email has attachments
			if(substr_count($emailArray['parts']['1']['headers']['content-disposition'], 'attachment') > 0)
				{
					$attachmentarray = array();
					$attachmentsearch = true;
					while($attachmentsearch != false)
						{
							$attachmentcounter++;
							if(substr_count($emailArray['parts'][$attachmentcounter]['headers']['content-disposition'], 'attachment') > 0)
								{
									$attachmentarray[$attachmentcounter] = $emailArray['parts'][$attachmentcounter]['headers']['content-type'];

									$rawData = $emailArray['parts'][$attachmentcounter]['body'];
									$fileName = $emailArray['parts'][$attachmentcounter]['ctype_parameters']['name'];
									if($fileName == null)
										{
											echo "ERROR: Could not find attachment name";
											$time = time();
											// give it a file name so it can be saved anyway
											$fileName = "$time"."_"."$attachmentCounter";
										}

									$attachmentLog .= "Attached: $fileName \n";

									$decodedData = base64_decode($rawData);

									if($saveAttachments == 1)
										{
											// save it to current directory
											$fh = fopen($fileName, 'w');
											fwrite($fh, $decodedData);
											fclose($fh);
										}
								}
							else
								{
									$attachmentsearch = false;
								}
						}
				}


		$from = $emailArray['headers']['from'];
		$to = $emailArray['headers']['to'];
		$messageId = $emailArray['headers']['message-id'];

		echo "return-path: ".$emailArray['headers']['return-path']."\nto: ".$_POST['to']."\nmessage: ".$emailArray['parts']['body'];
		// This may be an array
		$deliveredTo = $emailArray['headers']['delivered-to'];

		$returnPath = $emailArray['headers']['return-path'];
		$replyTo = $emailArray['headers']['reply-to'];
		$contentType = $emailArray['headers']['content-type'];
		$contentTransferEncoding = $emailArray['headers']['transfer-encoding'];

		// if the key-value posting option is used get that data
		if($_POST['to'] != null)
			{
				echo "post to is not null";
				$returnPath = $_POST['return-path'];
				$messageId = $_POST['message-id'];
				$subject = $_POST['subject'];
				$from = $_POST['from'];
				$to = $_POST['to'];
				$replyTo = $_POST['reply-to'];
				$deliveredTo = $_POST['delivered-to'];
				$contentType = $_POST['content-type'];
				$contentTransferEncodingHtml = $_POST['content-transfer-encoding-html'];
				$contentTransferEncoding = $_POST['content-transfer-encoding'];
				$messagehtml = $_POST['body_html'];
				$message = $_POST['body'];

				// save attachements
				if(count($_POST['attachment']) > 0)
					{
						foreach($_POST['attachment'] as $key => $value)
							{
								$attachmentName = $value['name'];
								$attachmentSize = $value['size'];
								$attachmentContentType = $value['content-type'];
								$attachmentLog .= "Attached: $attachmentName size: $attachmentSize type: $attachmentContentType \n";
								$decodedData = base64_decode($value['body']);

								if($saveAttachments == 1)
									{
										// save it to current directory
										$fh = fopen($attachmentName, 'w');
										fwrite($fh, $decodedData);
										fclose($fh);
									}
							}
					}
			}


		// $message and $messagehtml are set above
		// if only an html version exists both $message and $messagehtml with be the same

	}


######################################################
## 	log the processed email to postTestLog.txt
##	(Make sure directory and/or file is writtable)
######################################################

$today = date("D M j G:i:s T Y");
$logEmailArray = print_r($emailArray, 1);
$postArray = print_r($_POST, 1);

$email .= "Processed on: $today";
$email .= "\n------------\n\$_POST array:\n------------\n$postArray";

$email .= "\n****************\n";
$email .= "Decoded values: ";
$email .= "\n****************";
$email .= "\n------------\n\$_POST['email'] value (JSON only):\n------------\n$postedEmail";
$email .= "\n------------\nEmail object (JSON only):\n------------\n$logEmailArray\n";
$email .= "\n------------\nfrom:\n------------\n$from\n";
$email .= "\n------------\nto:\n------------\n$to\n";
$email .= "\n------------\ndelivered-to:\n------------\n$deliveredTo\n";
$email .= "\n------------\nreturn-path:\n------------\n$returnPath\n";
$email .= "\n------------\nreplyTo:\n------------\n$replyTo\n";
$email .= "\n------------\nmessage-id:\n------------\n$messageId\n";
$email .= "\n------------\ncontent-type:\n------------\n$contentType\n";
$email .= "\n------------\ncontent-transfer-encoding:\n------------\n$contentTransferEncoding\n";
$email .= "\n------------\nsubject:\n------------\n$subject\n";
$email .= "\n------------\nmessage:\n------------\n$message\n";
$email .= "\n------------\nmessagehtml:\n------------\n$messagehtml\n";
$email .= "\n------------\nAttachments:\n------------\n$attachmentLog\n";

$added = "Starting";
$time = time();

$myFile = "lead-handler-$time.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$log = "Email post log:\n$email \n";
echo "Email error=$error";
if( $error !== 1 )
{
	$prospect = new Prospect($from, $message);
	echo "\n---------------------Prospect-----------------\n";
	echo $prospect;
	echo "\n----------------------------------------------\n";
	if( ! $useTestEmail )
	{
		try {
			$added = addContactNote($today, $from, $prospect.get_email(), $messageId, $subject, "\n------\n$prospect\n---------\n", $attachmentLog, $postArray);
			$log .= "Prospect $prospect.get_email() created for $from at $added \n";
			echo "\nProspect $prospect.get_email() created for $from at $added \n";
		}
		catch (exception $e) {
			$log .= "#Posted $today#, Exception $e occurred attempting to add $prospect.get_email() for $from";
			echo "\n#Posted $today#, Exception $e occurred attempting to add $prospect.get_email() for $from\n";
		}
	}
}
fwrite($fh, $log);
fclose($fh);

// return a confirmation to mailnuggets
echo "#Posted $today#, $log";



?>
