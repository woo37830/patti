#!/bin/bash
#
# Run a mysql dump and attach a date to the dumpfile for use by restore
#
# Usage: backup [daily|weekly|monthly|yearly], default daily
#
# directories ~/backups/daily, etc. should exist and be writable
#
# The remote server and the directory on it ~/backups/aws must
# exist and be writable.
#
PERIOD='daily'
if [  "$#" -gt 0  ]; then
   PERIOD=$1;
fi
DATE=`date +%Y%m%d`

/usr/bin/mysqldump -u root -ppass-word database > /home/userid/backups/$PERIOD/database-$DATE.sql
if [ "$?" -ne 0 ]; then
	echo "$DATE backup FAILED to $PERIOD because $?"
else
	echo "$DATE backup SUCCEEDED to $PERIOD with size: `du /home/userid/backups/$PERIOD/database-$DATE.sql`"
/usr/bin/rsync -avz --ignore-existing --progress --rsh "ssh -l remote-user-id"  /home/userid/backups/$PERIOD/database-$DATE.sql remote-server:backups/aws > /dev/null
   if [ "$?" -ne 0 ]; then
	echo "$DATE rcp FAILED to destination because: $?"
   else
	echo "$DATE rcp SUCCEEDED to destination"
   fi
fi
