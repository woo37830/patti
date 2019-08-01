<?php
require("header_footer.php");
$first = $_GET["first"];
$last = $_GET["last"];
$email = $_GET["email"];
$passwd = $_GET["password"];
$_SESSION["email"] = $email;
$_SESSION["password"] = $passwd;
$form = <<<EOT
<h1>Here we are in the page which will create the account. It will not be visible.</h1>
<form id="form" method="POST" action="https://secure.engagemorecrm.com/api/2/AddAccount.aspx" >
<input type="hidden" name="response_type" value="json" />
<input type="text" name="email" value="$email" />
<input type="password" name="password" value="$passwd" />
<input type="hidden" name="apipassword" value="ie6n85dF826iYe5npA" />
<input type="hidden" name="apiusername" value="4K9vV0InIxP5znCa7d" />
<input type="hidden" name="group" value="RE FreeTrial" />
<input type="hidden" name="affiliatecode" value="1001" />
<input type="submit" name="submit" id="submit" value="Submit" />
</form>
EOT;

echo page_header();
echo $form;
echo git_footer();
echo "</body>";
echo "</html>";
?>
