CREATE DATABASE IF NOT EXISTS user_db;
USE user_db;

DROP TABLE IF EXISTS user_info;
CREATE TABLE user(
id INT AUTO_INCREMENT,
user_name VARCHAR(100),
user_email VARCHAR(100),
user_password VARCHAR(100),
PRIMARY KEY(id, user_email)
);




