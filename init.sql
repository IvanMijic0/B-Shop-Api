CREATE DATABASE IF NOT EXISTS my_database;
GRANT ALL PRIVILEGES ON my_database.* TO 'my_user'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
