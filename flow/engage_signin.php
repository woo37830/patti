<?php
$form = <<<EOT
<html>
<head>
</head>
<body>
<h1>EngageMoreCRM</h1>
<hr />
<form action="engagemore.php">
<table>
<tr><td>Email: </td><td><input type="text" name="email"/></td></tr>
<tr><td>Password: </td><td><input type="password" name="password" /></td></tr>
</table>
<input type="submit" value="Sign In" />
</form>
<h3>Or, Sign up for a free trial</h3>
<form action="signup.html">
<table>
<tr><td>Email: </td><td><input type="text" name="email"/></td></tr>
</table>
<input type="submit" value="Sign Up" />
</form>
</body>
</html>
EOT;
  echo $form;
?>
