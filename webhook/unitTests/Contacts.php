<?php

require '../Contact.php';

$contact = Contact::read(2607, 'ralph.jones@gmail.com');
if( $contact->get_errMessage() != '' )
{
  echo "\n".$contact->get_errMessage()."\n";
}
echo "All Done!";

?>
