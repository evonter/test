LEMP(vsftpd,nginx,mysql-server,php8.1-fpm php-mysql,composer)

mysql
CREATE DATABASE db;
CREATE TABLE db.customers (id INT AUTO_INCREMENT, name VARCHAR(64), email VARCHAR(64), phone VARCHAR(64), PRIMARY KEY(id));


composer.json

{
    "require":{
        "php":">=8.1.0",
        "twig/twig":">=3.5.1",
        "joshcam/mysqli-database-class":">=2.9.3"
    }
}
