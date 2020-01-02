<?php
  /**
   * AllClients Account ID and API Key.
   */
$account_id = '[ SET ACCOUNT ID ]';
$api_key    = '[ SET API KEY ]';
$text_magic_api_key = '[AIMRx8N1BKnpfiIAJEVUSdmqUbTt08]';
$text_magic_to_address = '[textmagic.com]';
$headers = "From: patti@engagemorecrm.com\r\n";

/**
 * The API endpoint and time zone.
 */
$api_endpoint = 'http://www.allclients.com/api/2/';
$api_timezone = new DateTimeZone('America/New_York');

/**
 * Newline character, to support browser or CLI output.
 */
$nl = php_sapi_name() === 'cli' ? "\n" : "<br>";

/**
  * Post data to URL with cURL and return result XML string.
  *
  * Outputs cURL error and exits on failure.
  *
  * @param string $url
  * @param array  $data
  *
  * @return string
  */
function post_api_url($url, array $data = array()) {
    global $nl;
    // Initialize a new cURL resource and set the URL.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // Form data must be transformed from an array into a query string.
    $data_query = http_build_query($data);
    // Set request type to POST and set the data to post.
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_query);
    // Set cURL to error on failed response codes from AllClients server,
    // such as 404 and 500.
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    // Set cURL option to return the response from the AllClients server,
    // otherwise $output below will just return true/false.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Post data to API.
    $output = curl_exec($ch);
    // Exit on cURL error.
    if ($output === false) {
       // It is important to close the cURL session after curl_error()
       printf("cURL returned an error: %s{$nl}", curl_error($ch));
       curl_close($ch);
       exit;
    }
    // Close the cURL session
    curl_close($ch);
    // Return response
    return $output;
 }

/**
 * Specify URL and form fields for GetContacts API function.
 */
$url = $api_endpoint . 'GetContacts.aspx';
$data = array(
    'accountid' => $account_id,
    'apikey'    => $api_key,
 );

if ( true ) {
#  if(mail("jwooten37830@me.com","Test Email","This is a test of emailing myself",$headers)) {
#   print("Sent an email{$nl}");
#} else {
#   print("Failed to send email message{$nl}");
#}
  if(mail("16028181667@textmagic.com","Test","This is a test text message from AlClients",$headers)) {
     print("Sent a text a message to Patti{$nl}");
  } else {
     print("Unable to send text message{$nl}");
  }
     exit;
}
?>
