<?php
require("header_footer.php");
$email = $_GET["email"];
$passwd = $_GET["password"];
$form = <<<EOT
<h1>Add Account</h1>
<hr />
<center>
<form method="POST" action="https://secure.engagemorecrm.com/api/2/AddAccount.aspx" >
Email: <input type="text" name="email" value="" autofocus /><br />
Password:<input type="password" name="password" value="" /><br />
<input type="hidden" name="apipassword" value="ie6n85dF826iYe5npA" />
<input type="hidden" name="apiusername" value="4K9vV0InIxP5znCa7d" />
<input type="hidden" name="group" value="RE FreeTrial" />
<input type="hidden" name="affiliatecode" value="1001" />
<br />
<input type="submit" name="submit" value="Submit" id="submit" />
</form>
EOT;

echo page_header();
echo $form;
echo git_footer();
echo "</center>";
echo "</body>";
echo "</html>";

