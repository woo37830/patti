<!DOCTYPE html> 
<html>
<head>
<title>jQuery Ajax Example with JSON Response</title>
</head>
 
<body>
  <?php if (isset($_POST['form_submitted'])): ?>
    <h2>Results from Add Account</h2>
    <hr />
    <h3>IT WORKED</h3>
    <?php else: ?>
<h1>Add Account</h1>
<hr />
<br />
<form method="post" action="https://secure.engagemorecrm.com/api/2/AddAccount.aspx">
  <input type="hidden" name="response_type" value="json" />
  <input type="text" name="email" value="xxx@yyy" /><br />
  <input type="password" name="password" value="123456" /><br />
  <input type="hidden" name="apipassword" value="ie6n85dF826iYe5npA" />
  <input type="hidden" name="apiusername" value="4K9vV0InIxP5znCa7d" />
  <input type="hidden" name="group" value="RE FreeTrial" />
  <input type="hidden" name="affiliatecode" value="1001" />
   <input type="hidden" name="form_submitted" value="1" />
  <br />
  <input type="submit" name="submit" id="submit" value="Submit" />
</form>

<?php endif; ?>
	
 
</body>
</html>
