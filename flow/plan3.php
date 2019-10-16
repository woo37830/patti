<?php
require("header_footer.php");
$first = $_GET["first"];
$last = $_GET["last"];
$passwd = $_GET["password"];

$form = <<<EOT
<h1>Plan3 $159/month</h1>
<hr />
<h1>This will not be visible</h1>
<h2>This page will be processed automagically by directing the user to Thrive Cart with account id</h2>
<h3>They will enter their credit card info and then be redirected back to sign in</h3>
<h3>They will have been automagically moved to a different group</h3>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  window.setTimeout(function() {
  window.location.href = "engagemore.php";
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
