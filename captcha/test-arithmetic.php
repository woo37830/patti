<?php
require('simpul_captcha.php');
require('header_footer.php');

echo page_header();

echo '<center><div id="label">';
   if( empty($_GET['result']) ) {
     echo "Hello there<br />";
  } else {
       echo "You entered " . $_GET['result'] . "<br />With email: " . $_GET['email'] . ".<br /> You are NOT a robot!<br />";
       echo "<br /><a href=\"http://localhost/captcha/test-arithmetic.php\" />Back</a>";
       echo "</center></div>";
       echo git_footer();
       echo "</body></html>";
     exit();
     }

$form = <<< 'EOF'
<form id="form" type="get" action="http://localhost/captcha/test-arithmetic.php" >
</div>
</center>
<center>
<input type="text" id="txt_name" name="result" value="" autofocus /><br />
<div id="email_field">Email: <input type="text" id="my-email" name="email" value=""  /><br /></div>
<input type="submit" name="submit" id="submit" value="Register"/>
</center>
</div>
EOF;

    echo "<div id=\"robotQ\">Are you a robot?" . simpul_captcha() . check_result_script() . not_a_robot() ;
    echo $form;
    echo git_footer();
    echo "</body></html>";
?>
