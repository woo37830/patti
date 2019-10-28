<?php
// The following url will produce an empty csv file except for headers
// http://localhost/patti/webhook/response_changed?key=amazinglysecurekey&type=response_changed&response=Hello&response[isComplete]=1&survey_id=3
// Verify the webhook origin by checking for the Webhook Key value you defined in SurveyTown
if( empty( $_REQUEST['key' ]) || $_REQUEST['key'] != "amazinglysecurekey" ){
 http_response_code(200);
 die();
}

// Look for the response_changed webhook event. Make sure the response is complete before processing.
if( $_REQUEST['type'] == "response_changed" && ! empty( $_REQUEST['response'] ) && $_REQUEST['response']['isComplete'] == 1 ){ 
 
 $columns = false;
 
 // Use a different CSV file for each survey
 $filename = "survey_responses_{$_REQUEST['survey_id']}.csv";
 
 // Create the CSV header if the file doesn't exist
 if( ! file_exists( $filename ) ){
 $columns = array( 
 "ID", 
 "IP Address", 
 "Start Time",
 "End Time"
   );
 }

// Add response meta data
 $response = array(
 $_REQUEST['response']['responseId'], 
 $_REQUEST['response']['ipAddress'], 
 $_REQUEST['response']['responseStart'], 
 $_REQUEST['response']['responseComplete']
 );
 
 // Loop through all the response data
 foreach( $_REQUEST['response'] as $key => $res ){
 // Questions will have integer keys
 if( is_int( $key ) ){
 // Handle multi value questions
 if( isset( $res['responses'] ) ){
 foreach( $res['responses'] as $choice => $value ){
 if( $columns ){
 $columns[] = strip_tags( $res['questionText'] ) . " ({$value['choiceText']})";
 }
 $response[] = $value['responseValue'];
 }
 // Handle single value questions
 }elseif( isset( $res['response'] ) ){
 if( $columns ){
 $columns[] = strip_tags( $res['questionText'] );
 }
 $response[] = $res['response']['responseValue'];
 }
 }
 }

// Open and write to the CSV
 $fp = fopen( $filename, "a" );
 if( $columns ){
 fputcsv( $fp, $columns );
 }
 fputcsv( $fp, $response );
 fclose( $fp ); 
}

http_response_code(200);
?>
