<?php
require("header_footer.php");
$email = $_GET["email"];

$form = <<<EOT
<h1>Here we are in the page where we notify that confirmation email was sent.. It will be visible.</h1>
<br/><p>An Email has been sent to $email.  Once you click on the link enclosed, you will be directed back to continue to signup process</p><br/>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  window.setTimeout(function() {
  window.location.href = "confirmed.php?email=$email";
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
