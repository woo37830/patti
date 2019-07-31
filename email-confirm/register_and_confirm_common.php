<?php // register_and_confirm_common.php


// COMMON CODE AVAILABLE TO ALL OUR REGISTER-AND-CONFIRM SCRIPTS


// SET ERROR REPORTING SO WE CAN DEBUG OUR SCRIPTS EASILY
error_reporting(E_ALL);

// CONNECTION AND SELECTION VARIABLES FOR THE DATABASE
$db_host = "jwooten37830.com"; // PROBABLY THIS IS OK
$db_name = "users_db";        // GET THESE FROM YOUR HOSTING COMPANY
$db_user = "root";
$db_word = "random1";

// OPEN A CONNECTION TO THE DATA BASE SERVER AND SELECT THE DB
$mysqli = new mysqli($db_host, $db_user, $db_word, $db_name);

// DID THE CONNECT/SELECT WORK OR FAIL?
if ($mysqli->connect_errno)
{
    $err
    = "CONNECT FAIL: "
    . $mysqli->connect_errno
    . ' '
    . $mysqli->connect_error
    ;
    trigger_error($err, E_USER_ERROR);
}


// AN EMAIL VALIDATION SCRIPT THAT RETURNS TRUE OR FALSE
function check_valid_email($email)
{
    // IF PHP 5.2 OR ABOVE, WE CAN USE THE FILTER
    // MAN PAGE: http://php.net/manual/en/intro.filter.php
    if (strnatcmp(phpversion(),'5.2') >= 0)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) return FALSE;
    }
    // IF LOWER-LEVEL PHP, WE CAN CONSTRUCT A REGULAR EXPRESSION
    else
    {
        $regex
        = '/'                       // START REGEX DELIMITER
        . '^'                       // START STRING
        . '[A-Z0-9_-]'              // AN EMAIL - SOME CHARACTER(S)
        . '[A-Z0-9._-]*'            // AN EMAIL - SOME CHARACTER(S) PERMITS DOT
        . '@'                       // A SINGLE AT-SIGN
        . '([A-Z0-9][A-Z0-9-]*\.)+' // A DOMAIN NAME PERMITS DOT, ENDS DOT
        . '[A-Z\.]'                 // A TOP-LEVEL DOMAIN PERMITS DOT
        . '{2,6}'                   // TLD LENGTH >= 2 AND =< 6
        . '$'                       // ENDOF STRING
        . '/'                       // ENDOF REGEX DELIMITER
        . 'i'                       // CASE INSENSITIVE
        ;
        if (!preg_match($regex, $email)) return FALSE;
    }

    // FILTER or PREG DOES NOT TEST IF THE DOMAIN OF THE EMAIL ADDRESS IS ROUTABLE
    $domain = explode('@', $email);
    if ( checkdnsrr($domain[1],"MX") || checkdnsrr($domain[1],"A") ) return TRUE;

    // EMAIL NOT ROUTABLE
    return FALSE;
}
// This function will display the die message with logo, and styled
    function die_msg( $msg ) {
    echo "<head>";
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://jwooten37830.com/patti/styles/example.css\">";
    echo "</head><body>";
    echo "<center>";
    echo '<img src="https://jwooten37830.com/patti/images/green_logo.gif" style="width:100px;height:100px"><br /><br /><br />';
    echo "</center>";
    echo "<div id=\"content\"><center>";
    echo "<div id=\"label\">";
    echo $msg;
    echo "</div>";
    echo "</center></div>";
    echo footer();
    echo "</body>";
    echo "</html>";
    die();
}
function footer() {
   $rev = exec('git rev-parse --short HEAD');
   $branch = exec('git rev-parse --abbrev-ref HEAD');
   echo "<footer><hr />Commit: $rev, Branch: $branch</footer>";
}
?>
