
CREATE DATABASE MarchMadness;

USE MarchMadness;
CREATE TABLE rj_posts (
  PostId int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  TopicId int NOT NULL,
  PostText varchar(2000) DEFAULT NULL,
  Created datetime DEFAULT NULL,
  Owner varchar(150) DEFAULT NULL
);

USE MarchMadness;
CREATE TABLE rj_topics (
  TopicId int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  Title varchar(150) DEFAULT NULL,
  Created datetime DEFAULT NULL,
  Owner varchar(150) DEFAULT NULL,
  Hot bit,
  Likes int NOT NULL DEFAULT 0
);

USE MarchMadness;
CREATE TABLE rj_authorize_users(
	UserId int NOT NULL PRIMARY KEY,
	Fname varchar(50),
	Lname varchar(50),
	UserName varchar(25),
	password varchar(40)
);

USE MarchMadness;
INSERT INTO rj_authorize_users VALUES(1000, 'Richie', 'Tarkowski', 'tarkowr', 'lebron06');

