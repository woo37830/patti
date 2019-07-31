<?php
$first = $_GET["first"];
$last = $_GET["last"];
$passwd = $_GET["password"];

$form = <<<EOT
<html>
<head>
</head>
<body>
<h1>Here we are in the page which will create the account</h1>
<h2>With the following values</h2>
<br/>First Name: $first 
<br/>Last Name: $last
<br/>Password: $passwd
<h2>This page will be processed automagically and redirect the user to the EngageMoreCRM page to sign in</h2>
</body>
</html>
EOT;

//$form = str_replace('%first%',$first,$form_str);
//$form = str_replace('%last%', $last,$form);
//$form = str_replace('%password%', $password, $form);

echo $form;
?>
