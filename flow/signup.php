<?php
require("header_footer.php");
$form = <<<EOT
<h1>Signup Page - linked from sales page</h1>
<hr />
<h2>Here we get the email and confirm that it is a good email</h2>
<br/>
<form type="get" action="confirmed.php">
Enter your email: <input type="text" name="email"/>
<input type="submit" name="submit" value="Submit" />
</form>
EOT;
  echo page_header();
  echo $form;
  echo git_footer();
  echo "</body>";
  echo "</html>";
?>
