<?php
   require 'config.ini.php';
   $last = exec('git log -1 --date=format:"%Y/%m/%d" --format="%ad"');
   $rev = exec('git rev-parse --short HEAD');
   $branch = exec('git rev-parse --abbrev-ref HEAD');
   $server = $config['PATTI_DATABASE_SERVER'];
   echo "<center>Current PHP version: " . phpversion('tidy')."&nbsp;&nbsp;Last Update: $last &nbsp;&nbsp;Commit: $rev &nbsp;&nbsp;&nbsp;Branch: $branch  &nbsp;&nbsp; Server: $server</center>";
?>
<script type="text/javascript" >
$("#info-img").click(function() {
    var $messageDiv = $('#info-div'); // get the reference of the div
    $messageDiv.slideDown(function() {
        $messageDiv.css("visibility", "visible"); // show and set the message
        setTimeout(function() {
            $messageDiv.slideUp();
        }, 20000);
    });
  });
function goBack()
{
  if( parameters.get('back') != null ) {
  window.location = parameters.get('back');
} else {
    window.location = "./index.php";
  }
}
function showError()
{
  if (parameters.get( "_error" ) != null)
  {
    $('#error-div').append(parameters.get('_error'));
    $("#error-div").css("visibility", "visible"); // show and set the error
    setTimeout( function() {
      $('#error-div').fadeOut('fast');
    }, 10000);
  }
}

</script>
