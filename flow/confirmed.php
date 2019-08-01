<?php
require("header_footer.php");
$email = $_GET["email"];
$form = <<<EOS
 <style>
    #form label{float:left; width:140px;}
    #error_msg{color:red; font-weight:bold;}
 </style>
<h1>Your email is confirmed.  Please complete the form</h1>
<hr />
When the submit button is pressed, the account will be created using the information from the form and the master api key to add the account to the trial group.  At the end of the addition, the user will be redirected to EngageMoreCRM to login.
<hr/>
<form id="form" action="create_account.php">
<table>
<tr><td>Email:</td><td>
<input type="text" name="email" value="$email" /></td></tr>
<tr><td>Password: </td><td><input type="password" name="password" id="password"/></td></tr>
<tr><td>Confirm: </td><td><input type="password" name="confirm" id="confirm_password"/></td></tr>
<tr><td>First Name: </td><td><input type="text" name="first" /></td></tr>
<tr><td>Last Name: </td><td><input type="text" name="last" /></td></tr>
</table>
<input type="submit" id="submit_id" name="submit" value="Submit" disabled="disabled"/>
</form>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>

 <script>
    $(document).ready(function(){
        var submitBtn = $("#submit_id");
        var passwordBox = $("#password");
        var confirmBox = $("#confirm_password");
        var errorMsg =  $('<span id="error_msg">Passwords do not match.</span>');

        // This is incase the user hits refresh - some browsers will maintain the disabled state of the button.
        submitBtn.removeAttr("disabled");

        function checkMatchingPasswords(){
            if(confirmBox.val() != "" && passwordBox.val != ""){
                if( confirmBox.val() != passwordBox.val() ){
                    submitBtn.attr("disabled", "disabled");
                    errorMsg.insertAfter(confirmBox);
                }
            }
        }

        function resetPasswordError(){
            submitBtn.removeAttr("disabled");
            var errorCont = $("#error_msg");
            if(errorCont.length > 0){
                errorCont.remove();
            }
        }


        $("#confirm_password, #password")
             .on("keydown", function(e){
                /* only check when the tab or enter keys are pressed
                 * to prevent the method from being called needlessly  */
                if(e.keyCode == 13 || e.keyCode == 9) {
                    checkMatchingPasswords();
                }
             })
             .on("blur", function(){
                // also check when the element looses focus (clicks somewhere else)
                checkMatchingPasswords();
            })
            .on("focus", function(){
                // reset the error message when they go to make a change
                resetPasswordError();
            })

    });
  </script>
EOS;
 echo page_header();
  echo $form;
  echo git_footer();
  echo "</body>";
  echo "</html>";
?>
