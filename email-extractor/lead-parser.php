
<?php
// Config
$dbuser = 'emlusr';
$dbpass = 'pass';
$dbname = 'email';
$dbhost = 'localhost';
$notify= 'services@.com'; // an email address required in case of errors
function mailRead($iKlimit = "")
    {
        // Purpose:
        //   Reads piped mail from STDIN
        //
        // Arguements:
        //   $iKlimit (integer, optional): specifies after how many kilobytes reading of mail should stop
        //   Defaults to 1024k if no value is specified
        //     A value of -1 will cause reading to continue until the entire message has been read
        //
        // Return value:
        //   A string containing the entire email, headers, body and all.

        // Variable perparation
            // Set default limit of 1024k if no limit has been specified
            if ($iKlimit == "") {
                $iKlimit = 1024;
            }
            $i_limit = 0;
            // Error strings
            $sErrorSTDINFail = "Error - failed to read mail from STDIN!";

        // Attempt to connect to STDIN
        $fp = fopen("php://stdin", "r");

        // Failed to connect to STDIN? (shouldn't really happen)
        if (!$fp) {
            echo $sErrorSTDINFail;
            exit();
        }

        // Create empty string for storing message
        $sEmail = "";

        // Read message up until limit (if any)
        if ($iKlimit == -1) {
            while (!feof($fp)) {
                $sEmail .= fread($fp, 1024);
            }
        } else {
            while (!feof($fp) && $i_limit < $iKlimit) {
                $sEmail .= fread($fp, 1024);
                $i_limit++;
            }
        }

        // Close connection to STDIN
        fclose($fp);

        // Return message
        return $sEmail;
    }
$email = mailRead();
// handle email
$lines = explode("\n", $email);
echo "\nlines: ".count($lines)."\n";
// empty vars
$from = "";
$subject = "";
$headers = "";
$message = "";
$splittingheaders = true;
for ($i=0; $i < count($lines); $i++) {
    if ($splittingheaders) {
        // this is a header
        $headers .= $lines[$i]."\n";

        // look out for special headers
        if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
            $subject = $matches[1];
        }
        if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
            $from = $matches[1];
        }
        if (preg_match("/^To: (.*)/", $lines[$i], $matches)) {
            $to = $matches[1];
        }
    } else {
        // not a header, but message
        $message .= $lines[$i]."\n";
    }

    if (trim($lines[$i])=="") {
        // empty line, header section has ended
        $splittingheaders = false;
    }
}

echo "\nInput\n";
for ($i=0; $i < count($lines); $i++) {
  echo "\n$lines[$i]";
}
echo "\n----------------\n";
echo "\nFrom - $from";
echo "\nTo - $to";
echo "\nSubject - $subject";
echo "\nMessage - \n$message";
echo "\n";
?>
