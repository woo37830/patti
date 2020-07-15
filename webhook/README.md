thrivecart.php provides the webhook to accept notifications from
Thrivecart that a customer has made, cancelled, or changed their subscription.
TODO:
1)  Check each path including cancel, change, affiliate, etc.  It appears
that on AWS I should be using the $conn->query($sql), and
$array = $result->fetch_assoc_array(); forms instead of mysqli_fetch, etc.
update all of these and test from the test script

2) created a curl script to try to test.  Get it working.

3) Look into affiliate handling NOPE PATTI SAYS DON'T NEED THIS 6/14/2020 11:45AM

new product - GET productid X and name from Patti when she's ready
on subscription-payment of new product move to impact - 99
In thrivecart.php, if event = subscription.payment and productid = X, then
  do the upgrade_account using the $99 Blast account number and group name.

TODO:  20200614
