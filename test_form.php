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
<form action="test_form.php" method="post">
 
Which is your favorite movie?
<input type="text" name="formMovie" maxlength="50">
 
<input type="submit" name="formSubmit" value="Submit">
 
</form>
</body>
</html>

