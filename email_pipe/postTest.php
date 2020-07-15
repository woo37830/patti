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
require_once '../webhood/mysql_common.php';
// Set to 1 to Save Attachements

// NOTE: By default, this saves attachments to the same directory as this script.
// it's recommended to set this to 0 when not testing the script, or change the directory where
// attachments are saved.  If someone emails you maliciousFile.php and can access the file, it is bad.

$saveAttachments = 1;

// strip slashes away if magic_quotes_gpc is on
if(get_magic_quotes_gpc() == 1)
	{
		$postedEmail = stripslashes($_POST['email']);
	}
else
	{
		$postedEmail = $_POST['email'];
	}

######################################################
##
## 	JSON FORMATTED EMAIL
## 	- The JSON encoded email below can be used for testing.
##
######################################################

// Set to TRUE for testing
$useTestEmail = FALSE;

if($useTestEmail == TRUE)
	{

		$postedEmail = '{"headers":{"from example@example.com  sat nov 20 22":"02:41 2010","return-path":"<example@example.com>","x-original-to":"mail@post.demo.mailnuggets.com","delivered-to":"mail@post.demo.mailnuggets.com","received":["from mail-wy0-f181.google.com (mail-wy0-f181.google.com [74.125.82.181]) by mail.mailnuggets.com (Postfix) with ESMTP id 21C3BE992 for <mail@post.demo.mailnuggets.com>; Sat, 20 Nov 2010 22:02:40 -0500 (EST)","by wyb36 with SMTP id 36so1316109wyb.40 for <mail@post.demo.mailnuggets.com>; Sat, 20 Nov 2010 19:02:40 -0800 (PST)","by 10.216.141.79 with SMTP id f57mr3446869wej.101.1290308560097; Sat, 20 Nov 2010 19:02:40 -0800 (PST)","by 10.216.86.129 with HTTP; Sat, 20 Nov 2010 19:02:40 -0800 (PST)"],"dkim-signature":"v=1; a=rsa-sha256; c=relaxed\/relaxed; d=gmail.com; s=gamma; h=domainkey-signature:mime-version:received:received:date:message-id :subject:from:to:content-type; bh=AJI14WuSViQ1Tn2HM8g83+mUumXYHUQie+kWqS2gc0A=; b=KjRM8LkON8LOms5luodtNlKWxGRisiTPU\/UoT7elsijQtGD61qymZin8Wmwt+gUyHq gxc+0WEOxZQdzjCLXz55gUe4kfV4hrvs6cjnDiRVtVNxBSR+eVXnLEwcsOh9B\/3DZ1rj 4ZKKMC1NEENjXLor+G\/FgAxPpioXDxMf1MSso=","domainkey-signature":"a=rsa-sha1; c=nofws; d=gmail.com; s=gamma; h=mime-version:date:message-id:subject:from:to:content-type; b=njXZSFk0ajP3vJU0gFzS4K8eXHFRo0Nb03IJniQ1fSjg+UKFOoJujE7Mfhgx\/TsJSu 1pd3j1ZeauW6BllKHws\/hcdWx9LvNHLiYubGdtWBvxX30lI2\/RGYUV+PRXIH3wfQ+01K m8H+PnJfFFzuSRZMHO\/6SaCmd1Pw2zW+RDEwQ=","mime-version":"1.0","date":"Sun, 21 Nov 2010 11:02:40 +0800","message-id":"<AANLkTik-UGO5D1mhiFJrpHBEqgNer3pRsnx=NghMG9Sj@mail.gmail.com>","subject":"Test email subject","from":"Tom Jones <example@example.com>","to":"mail@post.demo.mailnuggets.com","content-type":"multipart\/alternative; boundary=00504502b1bd6011f10495875f8d"},"ctype_primary":"multipart","ctype_secondary":"alternative","ctype_parameters":{"boundary":"00504502b1bd6011f10495875f8d"},"parts":[{"headers":{"content-type":"text\/plain; charset=ISO-8859-1"},"ctype_primary":"text","ctype_secondary":"plain","ctype_parameters":{"charset":"ISO-8859-1"},"body":"Test email message\n\n"},{"headers":{"content-type":"text\/html; charset=ISO-8859-1"},"ctype_primary":"text","ctype_secondary":"html","ctype_parameters":{"charset":"ISO-8859-1"},"body":"<b>Test email message<\/b><br>\n\n"}]}';

	}



if(!(function_exists('json_decode')))
	{
		echo " ERROR: Missing json_decode function. ";
		$error = 1;
	}

if(count($_POST) < 1)
	{
		echo " ERROR: No data posted. ";
		$error = 1;
	}

if(function_exists('json_decode') && $postedEmail != null)
	{
		if(!($emailObject = json_decode($postedEmail)))
			{
				// the string is not valid JSON
				echo " ERROR: Not valid JSON. ";
				$error = 1;
			}
	}

// it's easier to access the data when converted to an array
function objectToArray( $object )
 	{
     	if( !is_object( $object ) && !is_array( $object ) )
		    {
		         return $object;
		    }

		if( is_object( $object ) )
		    {
		         $object = get_object_vars( $object );
		    }

     	return array_map( 'objectToArray', $object );
	}

// convert object to an array recursively
$emailArray = objectToArray($emailObject);


function decodeQuotedPrintable ($message)
	{
		// Remove soft line breaks
        $message = preg_replace("/=\r?\n/", '', $message);

        // Replace encoded characters
		$message = preg_replace('/=([a-f0-9]{2})/ie', "chr(hexdec('\\1'))", $message);

		return $message;
	}

// get key variables from $emailArray object if no errors
if($error != 1)
	{

		$subject = $emailArray['headers']['subject'];

		if($emailArray['body'] != null)
			{
				$message = $emailArray['body'];
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

		// This may be an array
		$deliveredTo = $emailArray['headers']['delivered-to'];

		$returnPath = $emailArray['headers']['return-path'];
		$replyTo = $emailArray['headers']['reply-to'];
		$contentType = $emailArray['headers']['content-type'];
		$contentTransferEncoding = $emailArray['headers']['transfer-encoding'];

		// if the key-value posting option is used get that data
		if($_POST['to'] != null)
			{
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

$added = addContactNote($today, $from, $to, $messageId, $subject, $message, $attachmentLog, $postArray);
$time = time();
$myFile = "postTestLog-$time.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$log = "Email post log:\n$email \n";

	$log .= "Email added as contact note to $to: $added \n";

fwrite($fh, $log);
fclose($fh);

// return a confirmation to mailnuggets
echo "#Posted $today#, note added to $to: $added";



?>
