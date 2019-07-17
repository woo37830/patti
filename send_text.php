<?php
   if( $_POST["name"] || $_POST["phone"] ) {
           if (preg_match("/[^A-Za-z'-]/",$_POST['name'] )) {
                      die ("invalid name and name should be alpha");
                            }
#                 $to = "1" . $_POST['phone'] . "@textmagic.com";
                 $to = $_POST['phone'];
                 $subject = "Test Message";
                 $msg = $_POST['message'] . ".  From: " . $_POST['name'];
                 $header = "From: jwooten37830@mac.com" .
#                 $header = "From: patti@engagemorecrm.com" .
#                 $header = "From: wooten.666@gmail.com" .
#                   "\r\n" . "Reply-To: jwooten37830@me.com\r\n" .
#                   "X-Mailer: PHP/" . phpversion() .
                   "\r\n" ; 
                       
                 echo "Sending text to: " . $to . "<br />"; 
                 echo "Header is: " . $header . "<br />";
                 echo "Message is: " . $msg . "<br />";

                 if( mail($to, $subject, $msg, $header) ) {
                   echo "Text sent successfully...";
                 } else {
                   echo "Text could not be sent...";
                     }
                 exit;
   }
?>
<html>
   <body>
   
      <form action = "<?php $_PHP_SELF ?>" method = "POST">
         Name: <input type = "text" name = "name" />
         Phone: <input type = "text" name = "phone" />
         Message: <input type = "text" name = "message" />
         <input type = "submit" />
      </form>
   
   </body>
</html>
