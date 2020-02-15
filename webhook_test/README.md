thrivecart.php provides the webhook to accept notifications from
Thrivecart that a customer has made, cancelled, or changed their subscription.
TODO:
1)  Check each path including cancel, change, affiliate, etc.  It appears
that on AWS I should be using the $conn->query($sql), and 
$array = $result->fetch_assoc_array(); forms instead of mysqli_fetch, etc.
update all of these and test from the test script

2) created a curl script to try to test.  Get it working.

3) Look into affiliate handling.
