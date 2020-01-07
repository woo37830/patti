<?php
require("header_footer.php");
$form = <<<EOT
<center>
<h1>Upgrade Now!</h1>
<hr />
<table width="80%">
<tr><th>Item</th><th>Starter</th><th>Go Getter</tn><th>Professional</th></tr>
<tr><td>Drips</td><td>5 Campaigns</td><td>10 Campaigns</td><td>All Campaigns</td></tr>
<tr><td>Contacts</td><td>1000</td><td>5000</td><td>Unlimited</td></tr>
<tr><td>Support</td><td>Email</td><td>Text</td><td>Voice</td></tr>
<tr><td>Cost</td><td>$59/month</td><td>$99/month</td><td>$159/month</td></tr>
<tr><td>Select</td><td><a href="plan1.php">Select</a></td><td><a href="plan2.php">Select</a></td><td><a href="plan3.php">Select</a></td></tr>
</table>
</center>
EOT;
    echo page_header();
    echo $form;
    echo git_footer();
    echo "</body>";
    echo "</html>";
?>

