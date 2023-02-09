LEMP(vsftpd,nginx,mysql-server,php8.1-fpm php-mysql,composer)

composer into project folder

---------------
mysql

CREATE DATABASE db;

CREATE TABLE db.customers (id INT AUTO_INCREMENT, name VARCHAR(64), email VARCHAR(64), phone VARCHAR(64), PRIMARY KEY(id));
