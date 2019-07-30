<?php
function page_header()
{
  $script = <<< 'EOS'
 <head>
 <link rel="stylesheet" type="text/css" href="https://jwooten37830.com/patti/styles/example.css">
 </head><body>
 <div id="content">
 <center>
 <img src="https://jwooten37830.com/patti/images/green_logo.gif" style="width:100px;height:100px">
 <br /><br /><br />
 </center>
 EOS;
   return $script;
}

function git_footer() {
  $rev = exec('git rev-parse --short HEAD');
  $branch = exec('git rev-parse --abbrev-ref HEAD');
  return "<footer><hr />Commit: $rev - Branch: $branch</footer>";
}
?>
