<?php

$verify_token = 'meatyhamhock';
$json_data = json_encode($_REQUEST);
if( $verify_token != $_REQUEST['hub_verify_token'] ) {
	die("Invalid verify token");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset="UTR-8">
<script type="text/javascript" class="init">
const params = new URL(location.href).searchParams;
let challenge=params.get("hub.challenge");
document.write(challenge);
let client=params.get("clientId");
document.write(client);
let name=params.get("name");
document.write("Name: ".name);
let email=params.get("email");
document.write("Email: ".email);
</script>
</head>
<body>


</body>
</html>
