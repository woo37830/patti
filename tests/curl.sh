#!/bin/bash
#
# Test the curl to subscribe to the apps events
#
curl \
  -F "object=page" \
  -F "callback_url=https://www.realestate-emal.com" \
  -F "fields=leadgen" \
  -F "verify_token=meatyhamhock" \
  -F "access_token=EAAEfSpGTHCIBAHIL7nK0ZAwC89DmQyV7zve74QsLYnXRZC1kVn2iJXfYanjWuFjGivwCBXYPW5sAecVOChpV55agpyr362Rmk2dUr2rhwlSHDdAnPZCOoxA9AIdCKdy29vFwA8yyI3DxhNJDZARFkjuB8JTfYlVy9WlXfFJ1JCNZCUPyHZAfiq8VWvoCCfoXXb7gwfLoite8NZCQPFo3wQ0vylgDPYS3COiIoT4HX6GngZDZD" \
  "https://graph.facebook.com/v12.0/1799147863615478/subscriptions"
  echo "All Done!"
