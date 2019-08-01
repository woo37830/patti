<?php
require('header_footer.php');
require('simpul_captcha.php');

echo page_header();
$server = $_SERVER['SERVER_NAME'];
echo '<center><div id="label">';
   if( empty($_GET['result']) ) {
     echo "Hello there<br />";
  } else {
       echo "You entered " . $_GET['result'] . "<br />With email: " . $_GET['email'] . ".<br /> You are NOT a robot!<br />";
       echo "<br /><a href=\"/patti/captcha/test-captcha.php\" />Back</a>";
       echo "</center></div>";
       echo git_footer();
       echo "</body></html>";
     exit();
     }

$form = <<< 'EOF'
<form id="form" type="get"  >
</div>
</center>
<center>
<input type="text" id="txt_name" name="result" value="" autofocus /><br />
<div id="email_field">Email: <input type="text" id="my-email" name="email" value=""  /><br /></div>
<div id="begone">Begone Robot! Sully my portal no more!</div>
<input type="submit" name="submit" id="submit" value="Register"/>
</center>
</div>
EOF;

    echo "<div id=\"robotQ\">Are you a robot?</div>" . simpul_captcha() . check_result_script() . not_a_robot() ;
    echo $form;
    echo git_footer();
    echo "</body></html>";
?>
