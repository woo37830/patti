<?php
require("header_footer.php");
$first = $_GET["first"];
$last = $_GET["last"];
$passwd = $_GET["password"];

$form = <<<EOT
<h1>Here we are in the page which will create the account. It will not be visible.</h1>
<h2>With the following values</h2>
<br/>First Name: $first 
<br/>Last Name: $last
<br/>Password: $passwd
<h2>This page will be processed automagically and redirect the user to the EngageMoreCRM page to sign in</h2>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  window.setTimeout(function() {
  window.location.href = "engage_signin.php";
  }, 10000);
});
</script>
EOT;

echo page_header();
echo $form;
echo git_footer();
echo "</body>";
echo "</html>";
?>
