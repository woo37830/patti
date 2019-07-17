<?php
  if($_POST['formSubmit'] == "Submit")
  {
      $varMovie = $_POST['formMovie'];
       print "You said " . $varMovie . " was your favorite!" . "<br />";
       exit;
   }
?>
<html>
<head>
</head>
<body>
<h1>Add Contact Note</h1>
<!-- https://www.allclients.com/api/2/GetContacts.aspx -->
<!-- See the all_contacts.php page for how to call -->
<!-- https://www.allclients.com/api/2/AddContactNote.aspx -->
<form action="test_form.php" method="post">

Which is your favorite movie?
<input type="text" name="formMovie" maxlength="50">

<input type="submit" name="formSubmit" value="Submit">

</form>
</body>
</html>
