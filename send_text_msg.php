<?php
   if( $_REQUEST["firstname"] || $_REQUEST["phone"] ) {
           if (preg_match("/[^A-Za-z'-]/",$_REQUEST['name'] )) {
                      die ("invalid name and name should be alpha");
                            }
#                 $to = "1" . $_REQUEST['phone'] . "@textmagic.com";
                 $to = $_REQUEST['phone'];
                 $subject = "Test Message";
                 $msg = $_REQUEST['firstname'] . "\r\n" . $_REQUEST['userinput'] . ".  From: " . $_REQUEST['contactid'];
                 $header = "From: jwooten37830@mac.com" .
#                 $header = "From: patti@engagemorecrm.com" .
#                 $header = "From: wooten.666@gmail.com" .
#                   "\r\n" . "Reply-To: jwooten37830@me.com\r\n" .
#                   "X-Mailer: PHP/" . phpversion() .
                   "\r\n" ; 
                       
                 print "Sending text to: " . $to . "<br />"; 
                 print "Subject is: " . $subject . "<br />";
                 print  "Header is: " . $header . "<br />";
                 print  "Message is: " . $msg . "<br />";

                 if( mail($to, $subject, $msg, $header) ) {
                   print  "Text sent successfully...";
                 } else {
                   print  "Text could not be sent...";
                     }
                 exit;
   }
?>
