<?php

/*
 * Provide AJAX access to getAccounts
 *
*/

require './get_all_accounts.php';

echo json_encode(getAccounts());

?>
