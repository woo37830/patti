
> sudo\ yum\ -y\ install\ httpd $(installs the httpd daemon)
> sudo\ yum\ -y\ install\ php $ (installs php)
> httpd\ -v $ (check the version apache httpd daemon)
> php\ -v $(check the version of php installed)
> sudo\ service\ httpd\ start $(start the apache server)
> sudo\ usermod\ -a \ G\ apache\ $ec2-user add ec2-user to apache)
> exit $( exit ssh session and then reconnect , to activate the above)
> groups $( See what groups you are in, should include apache )
> sudo\  chown \ R\ $ec2-user:apache\ /var/www (allow these groups access to the web server area www)
> sudo\ chmod\ 2775\ /var/www $(set permissions for user and group to allow writing and reading)
> find\ /var/www\ -type\ d \ exec\ sudo\ chmod\ 2775\ {}\ \;$ (set directories all to this)
> find /var/www\ -type \  -exec\ sudo\ chmod\ 0664\ {}\ \; $(files to this)
> echo ''<?php phpinfo(); ?>'' > /var/www/html/phpinfo.php$
> sudo\ rm\ -rf\ /etc/httpd/conf.d/welcome.conf $
(remove the default welcome page so that your php page can be accessed)
> sudo\ service\ httpd \ estart $(restart the apache server)

In your browser
     http://elastic-ip/phpinfo.php$
> sudo\ yum\ install\ git $
> cd\ /var/www/html$
> sudo\ git\ clone\ https://github.com/your-area/your.git$
