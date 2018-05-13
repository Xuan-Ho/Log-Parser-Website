# WebApp-LogParser  www.log-parser.com
Website That Parse Uploaded Log File


!!!! DONT LEAVE ANY TEXT FILE IN THE PROJECT ROOT FOLDER/DIRECTORY OR IT WILL MESS UP THE OUTPUT !!!!
---------------------------------------------
1) XAMPP - For Local Host Testing Environment
https://www.apachefriends.org/index.html
---------------------------------------------
Local Hosting Use: XAMPP on Windows 

Server/Cloud HostUse: LAMP with phpMyAdmin on Ubuntu 16.4
Website (hosted by DigitalOcean): www.log-parser.com


---------------------------------------------
2) PHP.ini Setting Setup
---------------------------------------------
REMEMBER TO EDIT YOUR PHP.INI TO SET THE MAX POST AND UPLOAD FILE SIZE!!!
LOOK FOR THESE VARIABLES IN PHP.INI AND SET THEM THESE VALUES!!!

   1) post_max_size=50M
   2) upload_max_filesize=50M
   3) max_execution_time=60

---------------------------------------------
3) Required Dependencies/Libraries
---------------------------------------------
//// To Display Chart/Graph ////
Python - matplotlib ('pip install matplotlib')

//// For zip file support  //// (My window localhost work without installing these, but my linux host server needed it in order to work)
PHP - ZZIPlib (http://zziplib.sourceforge.net/download.html), PECL(https://pecl.php.net/package/zip)

//// Framework: Bootstrap 4  ////
It is embeded on the codes and used via CDN (Content Delivery Network) and not locally so no download files need.


---------------------------------------------
4) Database Connection - Setup
(from included file 'mysql_connection.php') 
---------------------------------------------
Changed these codes snippet from the included file to your own database connection config

 //Database Connection Config
    public $hostserver = ' "localhost" or your_server_ip';  
    public $username = 'root';
    public $password = '';
    public $database_name = 'user_db';

---------------------------------------------
5) Database Setup - MySQL
(from included file 'database_creation.sql') 
---------------------------------------------
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

//
	Executed this queries first through phpmyAdmin to create the database after setting up 
	datable connection. The database must be manually created with these queries!!!
//
