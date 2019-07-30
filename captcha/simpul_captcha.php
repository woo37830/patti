<?php
// simpul_captcha.php provides the captcha algorithm which is an arithmetic
// expression and expects the answer to match what was expected.  The imagegammacorrect
// answer is held in a hidden field in this case, but could use the session_start
// instead.
//

function simpul_captcha() {
  //    session_start();
      $num1 = rand(1,20);
      $num2 = rand(1,20);
  //    echo $num1 . PHP_EOL . $num2 . PHP_EOL;
      $op = rand(0,1);
  //    echo $op . PHP_EOL;
      if( $num1 < $num2 ) {
          $tmp = $num1;
          $num1 = $num2;
          $num2 = $tmp;
      }
      $value = 0;
      $operator = "";
      if( $op === 1 ) {
          $value = $num1 - $num2;
          $operator = "-";
          } else {
          $value = $num1 + $num2;
          $operator = "+";
          }
   printf ("<input id=\"hidden\" type=\"hidden\" name=\"result\" value=\"" . $value . "\" />");
   $expression = "<br/>Enter the result of: " . $num1 . " " . $operator . " " . $num2 ;
   return $expression;
}

function check_result_script() {
  $script = <<< 'EOS'
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js" ></script>

  <script type="text/javascript">
      $(document).ready(function(){
          $("#welcome").hide();
          $("#email_field").hide();
          $("#robotQ").show();
          $("#submit").attr('disabled',true);

          $("#txt_name").blur(function(){
              if( parseInt($(this).val()) == parseInt($('#hidden').val()) ) {
                $("#email_field").show();
                $("#txt_name").hide();
        //        $("#robotQ").hide(); // This statement causes a submit or at least removes the entire form
        //        Here I would like to put the focus on the input field for my-email.
                $("#welcome").show();
                $("input[type=submit]").attr('disabled',false);
          } else {
                $("input[type=submit]").attr('disabled','disabled');
                alert("Begone robot!");
                $("#txt_name").val("");
              }
          })
     });
  </script>
EOS;
  return $script;
}

function not_a_robot($msg="Welcome human,<br />You have passed the Bene Gesserit Gom Jabar test!<br />Please continue with your registration.<br />") {
  return "<div id=\"welcome\">" . $msg . "</div>";
}
?>
