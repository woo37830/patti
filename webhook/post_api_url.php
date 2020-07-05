<?php
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
	//echo "\ndata_query: " . $data_query . "\n";

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
	//echo "\noutput (" . gettype($output) . "): " . $output . "\n";

	// Exit on cURL error.
	if ($output === false) {
		// It is important to close the cURL session after curl_error()
		$retStr = curl_error($ch);
		printf("cURL returned an error: %s<br />", $retStr);
		$email = $data["email"];
		$logStr = "";
                printf("<br />email: %s, %s<br />",$email,$logStr);
		logit($email,$logStr, $retStr);
		curl_close($ch);
                return $retStr;
	}

	// Close the cURL session
	curl_close($ch);

	// Return response
	return $output;
}
?>
