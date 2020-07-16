<?php
namespace PHPMailer\PHPMailer;
use PHPMailer;
use SMTP;

function sendNotification($to_email, $theSubject, $theBody)
{

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require './vendor/autoload.php';


// Replace sender@example.com with your "From" address.
// This address must be verified with Amazon SES.
$sender = 'jwooten37830@mac.com';
$senderName = 'John Wooten';

// Replace recipient@example.com with a "To" address. If your account
// is still in the sandbox, this address must be verified.
$recipient = 'support@engagemorecrm.com';

// Replace smtp_username with your Amazon SES SMTP user name.
$usernameSmtp = 'AKIAS7F5B4AMF5XWV7W3';

// Replace smtp_password with your Amazon SES SMTP password.
$passwordSmtp = 'BACYnop52dFIQuwGD8lOmooCTw5mBEpHkulTi8RN969B';

// Specify a configuration set. If you do not want to use a configuration
// set, comment or remove the next line.
//$configurationSet = 'ConfigSet';

// If you're using Amazon SES in a region other than US West (Oregon),
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
// endpoint in the appropriate region.
$host = 'email-smtp.us-west-2.amazonaws.com';
#$port = 993;
$port = 587;

// The subject line of the email
$subject = $theSubject; 

// The plain-text body of the email
$bodyText = $theBody . ' User $to_email has cancelled'; 

// The HTML-formatted body of the email
$bodyHtml = $bodyText; 

$mail = new PHPMailer();
$retVal = false;
try {
    // Specify the SMTP settings.
    $mail->isSMTP();
    $mail->setFrom($sender, $senderName);
    $mail->Username   = $usernameSmtp;
    $mail->Password   = $passwordSmtp;
    $mail->Host       = $host;
    $mail->Port       = $port;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'tls';
//    $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

    // Specify the message recipients.
    $mail->addAddress($recipient);
    // You can also add CC, BCC, and additional To recipients here.

    // Specify the content of the message.
    $mail->isHTML(true);
    $mail->Subject    = $subject;
    $mail->Body       = $bodyHtml;
    $mail->AltBody    = $bodyText;
    $mail->Send();
    echo "Email with $bodyText sent to $recipient on port: $port!" , PHP_EOL;
    $retVal = true;
} catch (phpmailerException $e) {
    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
} catch (Exception $e) {
    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
}
	return $retVal;
}

//$val = sendNotification('jwooten37830@icloud.com', 'Test notify', 'Test Body');
//   echo "sendNotification returned $val";
?>

