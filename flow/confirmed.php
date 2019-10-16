<?php
session_start(); // Start a session for storing things
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
<form id="form" action="https://secure.engagemorecrm.com/api/2/AddAccount.aspx">
<table>
<tr><td>Email:</td><td>
<input type="text" name="email" value="$email" /></td></tr>
<tr><td>Password: </td><td><input type="password" name="password" value="" id="password"/></td></tr>
<tr><td>Confirm: </td><td><input type="password" value="" name="confirm" id="confirm_password"/></td></tr>
</table>
<input type="hidden" name="response_type" value="json" />
<input type="hidden" name="apipassword" value="ie6n85dF826iYe5npA" />
<input type="hidden" name="apiusername" value="4K9vV0InIxP5znCa7d" />
<input type="hidden" name="group" value="RE FreeTrial" />
<input type="hidden" name="affiliatecode" value="1001" />
<br />
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

            //callback handler for form submit
           $("#form").submit(function(e)
           {
           var postData = $(this).serializeArray();
           var formURL = $(this).attr("action");

           $.ajax(
           {
            url : formURL,
            type: "POST",
            crossDomain: true,
            // This is the important part
            xhrFields: {
                withCredentials: true
            },
            data : postData,
            dataType: "json",
         success:function(data, textStatus, jqXHR) 
         {
           //data: return data from server
            alert('it worked');
            alert("Result: "+data.result+", Message: "+data.message);
          },
          error: function(jqXHR, textStatus, errorThrown) 
         {
            //if fails   
            alert('it didnt work');   
          }
        });
        e.preventDefault(); //STOP default action
//        e.unbind();
    });

  $("#form").submit(); //Submit  the FORM

    });
  </script>
EOS;
 echo page_header();
  echo $form;
  echo git_footer();
  echo "</body>";
  echo "</html>";
?>
