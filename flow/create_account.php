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
<h2>With the following values</h2>
<br/>First Name: $first 
<br/>Last Name: $last
<br />Email: $email
<br/>Password: $passwd
<h2>This page will be processed automagically and redirect the user to the EngageMoreCRM page to sign in</h2>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  var email_addr = "$email";
  var password = "$passwd";
  var group = "RE%20Free%20Trial";
  var affiliatecode = "1001";
  var trialdays = "14";
  var billingstatus = "Trial";
  var master_api_key = 'ie6n85dF826iYe5npA';
  var uid = '4K9vV0InIxP5znCa7d';
  var dataStr = 'email='+email_addr+'&password='+password+'&apipassword='+master_api_key+'&apiusername='+uid+'&group='+group+'&affiliatecode='+affiliatecode+'&trialdays='+trialdays+'&billingstatus='+billingstatus;
// AJAX Code submit form
$.ajax({
  type: "POST",
  url: "https://secure.engagemorecrm.com/api/2/AddAccount.aspx",
  data: dataStr,
  cache: false,
  success: function(result) {
     if( result['error'] == false) {
        alert(result);
     }
     else {
        alert('An Error occurred: '+result['error']);
     }
}
});
});
//  window.setTimeout(function() {
//  window.location.href = "engage_signin.php";
//  }, 10000);
//});
</script>
EOT;

echo page_header();
echo $form;
echo git_footer();
echo "</body>";
echo "</html>";
?>
