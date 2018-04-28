CREATE DATABASE IF NOT EXISTS user_db;
USE user_db;

DROP TABLE IF EXISTS user;
CREATE TABLE user(
id INT AUTO_INCREMENT,
name VARCHAR(100),
email VARCHAR(100),
password VARCHAR(100),
PRIMARY KEY(id, email)
);

DROP TABLE IF EXISTS admin;
CREATE TABLE admin
(
id INT AUTO_INCREMENT,
firstname VARCHAR(100),
lastname VARCHAR(100),
email VARCHAR(100),
password VARCHAR(100),
PRIMARY KEY(id, email)
);

INSERT INTO `admin`(`firstname`, `lastname`, `email`, `password`) VALUES ('Xuan','Ho','admin@gmail.com','a');

DROP TABLE IF EXISTS usermalware;
CREATE TABLE usermalware
(
name VARCHAR(100),
signature VARCHAR(255),
PRIMARY KEY(signature)
);

DROP TABLE IF EXISTS malware;
CREATE TABLE malware
(
name VARCHAR(100),
signature VARCHAR(255),
PRIMARY KEY(signature)
);


DROP TRIGGER IF EXISTS addMalware;
DELIMITER //
CREATE TRIGGER addMalware
AFTER DELETE ON usermalware
FOR EACH ROW
BEGIN
INSERT INTO malware(name, signature) VALUES (old.name, old.signature);
END;//
DELIMITER ;




