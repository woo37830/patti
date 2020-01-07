<?php
require("header_footer.php");
$form = <<<EOT
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
<form action="email_sent.php">
<table>
<tr><td>Email: </td><td><input type="text" name="email"/></td></tr>
</table>
<input type="submit" value="Sign Up" />
</form>
EOT;
 echo page_header();
 echo $form;
 echo git_footer();
 echo "</body>";
 echo "</html>";
?>
