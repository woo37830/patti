> mysql -u root -p
   (enter root password)
> mysql> CREATE USER 'new-userid'@'%' IDENTIFIED BY 'passwd';
   Query OK, 0 rows affected (0.00 sec)

> mysql> GRANT ALL PRIVILEGES ON . TO ''new-userid''@''\%'';
> mysql$>$quit;

After completing the permissions described, from a remote terminal session try:

> mysql -h elastic-ip -u userid -p
   (enter passwd from CREATE USER)

You should be connected to the mysql database on AWS.
Try:

> show databases;
   ( should show several including users\_db )
> use users\_db;
> show tables;
> select * from users limit 10;
> exit

Before the above script will work, you must ensure that the rcp command will work from the AWS EC2 instance. I did this by:


>  ssh-keygen (on the AWS EC2 instance, saving key in .ssh/id\_rsa)
> ssh-copy-id remote\_userid@remote\_server (copies the key)
> ssh remote\_userid@remote\_server ( test that you can ssh to the server)
Now, we create the crontab entries that will cause the mysql dumps to be made on a particular schedule. To do this:


>  crontab -e (then enter the following)

# Minute	Hour	Day of Month	Month    Day of Week         Command
# (0-59)	(0-23)	   (1-31)	 (1-12)	   (0-6)
    0            3   	    * 	           * 	     *   	/home/userid/bin/backup daily >> /home/userid/logs/daily
    0            4          *              *         0          /home/userid/bin/backup weekly >> /home/userid/logs/weekly
    0            5          1              *         *          /home/userid/bin/backup monthly >> /home/userid/logs/monthly
    0            0          1              1         *          /home/userid/bin/backup yearly >> /home/userid/logs/yearly


(then close the crontab file with shift-Z shift-Z

You can inspect the file by typing:

> crontab -l ( and you should see the above )
