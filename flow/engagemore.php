<?php
require("header_footer.php");
$form = <<<EOT
<h1>EngageMoreCRM (Mockup)</h1>
<hr />
<br />
You have 13 days left in your free trial
<br />
<table>
<tr><td>| Home | </td><td>Contacts | </td><td>Client Actions | </td><td><a href="upgrade.php">Upgrade</a> |</td></tr>
</table>
<br />
<br />
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. </p>

<p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. </p>

<a href="engage_signin.php">Start Over</a>
</body>
</html>
EOT;
 echo page_header();
 echo $form;
 echo git_footer();
 echo "</body>";
 echo "</html>";
?>
