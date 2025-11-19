
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";


CREATE DATABASE IF NOT EXISTS career_tool DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE career_tool;

CREATE TABLE users (
id int(11) NOT NULL AUTO_INCREMENT,
username varchar(50) NOT NULL,
phone varchar(20) DEFAULT NULL,
password_hash varchar(255) NOT NULL,
created_at timestamp NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (id),
UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE career_submissions (
id int(11) NOT NULL AUTO_INCREMENT,
user_id int(11) NOT NULL,
name varchar(255) DEFAULT NULL,
age int(3) DEFAULT NULL,
qualification varchar(255) DEFAULT NULL,
excites text DEFAULT NULL,
dream_job varchar(255) DEFAULT NULL,
location varchar(255) DEFAULT NULL,
submitted_at timestamp NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (id),
KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE career_submissions
ADD CONSTRAINT career_submissions_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

COMMIT;
