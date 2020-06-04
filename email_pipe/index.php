#!/usr/bin/php -q
<?php // /email_pipe/index.php


// THIS IS AN EMAIL PIPE SCRIPT.
// THIS SCRIPT IS STARTED AUTOMATICALLY FOR EACH MESSAGE.
// NOTE THAT THIS SCRIPT IS ABOVE THE /public_html/ DIRECTORY TO PREVENT ACCIDENTAL EXECUTION


// --> HOW DO WE KNOW WHICH EMAIL MESSAGES GET SENT HERE?
// THIS SCRIPT RECEIVES MESSAGES SENT TO email_pipe@your.org
// CREATE AN EMAIL MAILBOX EXCLUSIVELY FOR AUTOMATED PROCESSING.
// SET UP AN EMAIL FORWARD FOR THAT MAILBOX IN cPANEL->EMAIL LIKE THIS:
// 1...5...10...15...20...25...
// |/home/{account}/email_pipe/index.php


// --> WHEN YOU UPLOAD, THIS SCRIPT WILL BE MARKED RW-R-R BUT THAT IS WRONG
// THIS SCRIPT MUST BE MARKED EXECUTABLE x0755
// YOU CAN USE FTP SOFTWARE TO CHMOD TO RWX-RX-RX


// --> NOTE THE FIRST LINE OF THIS SCRIPT MUST SAY #!/usr/bin/php -q STARTING IN COLUMN ONE
// 1...5...10...15...20...25...
// #!/usr/bin/php -q
// <?php ... PROGRAM CODE FOLLOWS


error_reporting(E_ALL);

// USE THE OUTPUT BUFFER - THIS DOES NOT HAVE BROWSER OUTPUT
ob_start();

// COLLECT THE INFORMATION HERE
$raw_email = '';


// TRY TO READ THE EMAIL FROM STDIN
if (!$stdin = fopen("php://stdin", "R"))
{
    echo "ERROR: UNABLE TO OPEN php://stdin \n";
}

// ABLE TO READ THE MAIL
else
{
    while (!feof($stdin))
    {
        $raw_email .= fread($stdin, 4096);
    }
    fclose($stdin);
}


// REMOVE MULTIPLE BLANKS - AND OTHER PROCESSING AS MIGHT BE NEEDED
$raw_email = preg_replace('/ +/', ' ', $raw_email);

// SPEW WHAT WE GOT, IF ANYTHING, INTO THE OUTPUT BUFFER
var_dump($raw_email);

// CAPTURE THE OUTPUT BUFFER AND SEND IT TO SOMEONE ELSE VIA EMAIL
$buf = ob_get_contents();
mail ('you@your.org', 'EMAIL PIPE DATA', $buf);
ob_end_clean();
?>
