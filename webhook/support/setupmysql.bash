
> sudo yum install mysql-server
> sudo chkconfig mysqld on
> sudo service mysqld start
> /usr/libexec/mysql55/mysqladmin -u root password ?passwd?

> sudo chkconfig mysqld on
> sudo chkconfig httpd on
> sudo chkconfig ?list (check for mysqld and httpd)

> mysql -u root -p
> create database users\_db;
> use users\_db;
> source users\_db.sql ( give file path if needed ) should see create statements
> show tables;
> exit;
