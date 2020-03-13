
/home/userid/backups/daily/*.sql {
rotate 14
daily
}
/home/userid/backups/weekly/*.sql {
rotate 8
weekly
}
/home/userid/backups/monthly/*.sql {
rotate 3
monthly
}
/home/userid/backups/yearly/*.sql {
rotate 3
yearly
}

This keeps 14 daily backups, 8 weekly backups, 3 monthly backups and 3 yearly backups and runs them as dictated in the logrotate.conf file. You can adjust the numbers and frequencies if desired. Now, it is necessary to edit the /etc/logrotate.conf file. I used the following :


> cat /etc/logrotate.conf
# see "man logrotate" for details
# rotate log files weekly
weekly

# keep 4 weeks worth of backlogs
rotate 4

# create new (empty) log files after rotating old ones
#create if you uncomment this you get 0 length files!

# use date as a suffix of the rotated file
#dateext ( I didn't want the file names changed, backup creates the names )

# uncomment this if you want your log files compressed
#compress (they were small and I didn't think I needed this)

# RPM packages drop log rotation information into this directory
include /etc/logrotate.d

# no packages own wtmp and btmp -- we'll rotate them here
/var/log/wtmp {
    monthly
    create 0664 root utmp
	minsize 1M
    rotate 1
}

/var/log/btmp {
    missingok
    monthly
    create 0600 root utmp
    rotate 1
}
# system-specific logs may be also be configured here.
