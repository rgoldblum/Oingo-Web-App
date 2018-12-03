drop schema oingo;
create schema oingo;
use oingo;

CREATE TABLE Users (
  uid INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(45) NOT NULL,
  password1 VARCHAR(45) NOT NULL,
  fname VARCHAR(45) NOT NULL,
  lname VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  latitude FLOAT( 10, 6 ) NOT NULL,
  longitude FLOAT( 10, 6 ) NOT NULL,
  PRIMARY KEY (uid));

/*user1 to user8 at courant, users 9 & 10 at random locations*/
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('rgold123','DB_Forever','Ross', 'GoldBlum','ross@nyu.edu',40.728580, -73.995520);/*at courant*/
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('kart','Matolu','Kartikeya', 'Shukla','ks5173@nyu.edu',40.728580, -73.995520);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user3','Matolu3','Kartikeya3', 'Shukla3','ks3@nyu.edu',40.728580, -73.995520);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user4','Matolu4','Kartikeya4', 'Shukla4','ks4@nyu.edu',40.728580, -73.995520);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user5','Matolu5','Kartikeya5', 'Shukla5','ks5@nyu.edu',40.728580, -73.995520);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user6','Matolu6','Kartikeya6', 'Shukla6','ks6@nyu.edu',40.728580, -73.995520);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user7','Matolu7','Kartikeya7', 'Shukla7','ks7@nyu.edu',40.728580, -73.995520);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user8','Matolu8','Kartikeya8', 'Shukla8','ks8@nyu.edu',40.728580, -73.995520);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user9','Matolu9','Kartikeya9', 'Shukla9','ks9@nyu.edu',49.729513, -74.996460);
INSERT INTO Users (username, password1, fname, lname, email, latitude, longitude)
VALUES ('user10','Matolu10','Kartikeya10', 'Shukla10','ks10@nyu.edu',45.729513, -74.996460);

CREATE TABLE Friendship (
  uid INT NOT NULL,
  friends_uid INT NOT NULL,
  PRIMARY KEY (uid,friends_uid),
  CONSTRAINT uid_fk FOREIGN KEY (uid) REFERENCES Users (uid),
  CONSTRAINT friends_uid_fk FOREIGN KEY (friends_uid) REFERENCES Users (uid));

/*1 friends with (2,3,4), 4 friends w/ (5,6,7), 7 friends w/ (8,9,10)*/
INSERT INTO Friendship VALUES (1,2);
INSERT INTO Friendship VALUES (1,3);
INSERT INTO Friendship VALUES (1,4);
INSERT INTO Friendship VALUES (2,1);
INSERT INTO Friendship VALUES (3,1);
INSERT INTO Friendship VALUES (4,1);
INSERT INTO Friendship VALUES (4,5);
INSERT INTO Friendship VALUES (4,6);
INSERT INTO Friendship VALUES (4,7);
INSERT INTO Friendship VALUES (5,4);
INSERT INTO Friendship VALUES (6,4);
INSERT INTO Friendship VALUES (7,4);
INSERT INTO Friendship VALUES (7,8);
INSERT INTO Friendship VALUES (7,9);
INSERT INTO Friendship VALUES (7,10);
INSERT INTO Friendship VALUES (8,7);
INSERT INTO Friendship VALUES (9,7);
INSERT INTO Friendship VALUES (10,7);

CREATE TABLE Schedules (
  sched_id INT NOT NULL AUTO_INCREMENT,
  activeDays VARCHAR(100) NOT NULL,
  startDate DATE NOT NULL,
  endDate DATE NOT NULL,
  startTime TIME NOT NULL,
  endTime TIME NOT NULL,
  PRIMARY KEY (sched_id));

INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Wed,Thu,Fri,Sat,Sun', '2018-11-04','2018-11-13','14:53:27', '22:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Tue,Wed,Thu,Fri,Sat,Sun', '2018-11-04','2018-11-19','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Wed,Thu', '2018-11-05','2018-11-30','13:53:27', '16:52:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Thu,Fri,Sat,Sun', '2018-11-06','2018-11-19','10:53:27', '18:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Wed,Thu,Fri,Sat,Sun', '2018-11-04','2018-11-13','14:53:27', '21:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Wed,Thu,Fri', '2018-11-07','2018-11-14','09:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Sun', '2018-11-04','2018-11-17','14:53:27', '18:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Tue,Fri,Sat,Sun', '2018-11-05','2018-11-13','14:53:27', '20:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Thu,Fri,Sat,Sun', '2018-11-05','2018-11-14','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Fri,Sat,Sun', '2018-11-05','2018-11-15','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Wed,Fri,Sat,Sun', '2018-11-05','2018-11-16','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-05','2018-11-20','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Tue,Fri,Sat,Sun', '2018-11-04','2018-11-20','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-11','2018-11-20','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Sat,Sun', '2018-11-05','2018-11-20','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-09','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-05','2018-12-20','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Sun', '2018-11-05','2018-11-20','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue', '2018-11-06','2018-11-21','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Wed', '2018-11-07','2018-11-21','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon', '2018-11-03','2018-11-21','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue', '2018-11-06','2018-12-21','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue', '2018-10-06','2018-12-21','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue', '2018-11-05','2018-11-21','10:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-09','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-01','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-02','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-03','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-04','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-05','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-06','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-07','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-08','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-09','2018-11-24','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-10','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-11-11','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-01','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-02','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-03','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-04','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-05','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-06','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-07','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-08','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-09','2018-11-25','11:53:27', '16:53:27');
INSERT INTO Schedules (activeDays,startDate, endDate, startTime, endTime)
VALUES ('Mon,Tue,Fri,Sat,Sun', '2018-09-10','2018-11-25','11:53:27', '16:53:27');

CREATE TABLE Note (
  nid INT NOT NULL AUTO_INCREMENT,
  uid INT NOT NULL,
  ntext TEXT NOT NULL,
  notePrivacy VARCHAR(45) NOT NULL,
  ntimestamp DATETIME NOT NULL,
  sched_id INT NOT NULL,
  radius FLOAT( 10, 6 ) NOT NULL,
  latitude FLOAT( 10, 6 ) NOT NULL,
  longitude FLOAT( 10, 6 ) NOT NULL,
  PRIMARY KEY (nid),
  CONSTRAINT uid1_fk FOREIGN KEY (uid) REFERENCES Users (uid),
  CONSTRAINT schedules_fk FOREIGN KEY(sched_id) REFERENCES Schedules(sched_id));

 /*notes 1 to 10 created by users1 to 10 at courant*/
INSERT INTO Note (uid, ntext, notePrivacy, sched_id, ntimestamp, radius, latitude, longitude)
VALUES (1, 'babe1,korean food', 'public', 1, '2018-11-04 14:53:27', 15.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (2, 'babe2,korean food', 'friends',2, '2018-11-03 10:53:27', 10.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (3, 'babe3,korean food', 'self', 3,'2018-11-04 10:53:27', 11.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (4, 'babe4,korean food', 'public',4, '2018-11-04 14:53:27', 15.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (5, 'babe5,korean food', 'public', 5,'2018-11-05 14:53:27', 15.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (6, 'babe6,korean food', 'friends', 6,'2018-11-06 14:53:27', 15.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (7, 'babe7,korean food', 'friends', 7,'2018-11-07 14:53:27', 15.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (8, 'babe8,korean food', 'self', 8,'2018-11-04 11:53:27', 15.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (9, 'babe9,korean food,spicy', 'public',9, '2018-11-04 10:53:27', 15.6, 40.728580, -73.995520);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (10, 'babe10,korean food,spicy', 'friends',10, '2018-11-04 15:53:27', 15.6, 40.728580, -73.995520);

INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (1, 'babe11,spicy,food', 'friends', 11,'2018-11-04 15:53:27', 15.6, 45.795133, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (2, 'babe12,spicy,food', 'friends', 12,'2018-11-04 15:53:27', 15.6, 45.751339, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (3, 'babe13,spicy,food', 'friends', 13,'2018-11-04 15:53:27', 15.6, 41.729513, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (4, 'babe14,spicy,food', 'friends', 14,'2018-11-04 15:53:27', 15.6, 43.729513, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (5, 'babe15,spicy,food', 'friends', 15,'2018-11-04 15:53:27', 15.6, 46.729513, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (6, 'babe16,spicy,food', 'friends', 16,'2018-11-04 15:53:27', 15.6, 47.729513, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (7, 'babe17,spicy,food', 'friends', 17,'2018-11-04 15:53:27', 15.6, 49.729513, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (8, 'babe18,spicy,food', 'friends', 18,'2018-11-04 15:53:27', 15.6, 41.729513, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (9, 'babe19,spicy,food', 'friends', 19,'2018-11-04 15:53:27', 15.6, 42.729513, -74.996460);
INSERT INTO Note (uid, ntext, notePrivacy, sched_id,ntimestamp, radius, latitude, longitude)
VALUES (10, 'babe20,spicy,food', 'friends', 20,'2018-11-04 15:53:27', 15.6, 43.729513, -74.996460);



CREATE TABLE Comments (
  cid INT NOT NULL AUTO_INCREMENT,
  nid INT NOT NULL,
  uid INT NOT NULL,
  ctext TEXT NOT NULL,
  ctimestamp DATETIME NOT NULL,
  PRIMARY KEY (cid),
  CONSTRAINT nid_fk FOREIGN KEY (nid) REFERENCES Note (nid),
  CONSTRAINT uid2_fk FOREIGN KEY (uid) REFERENCES Users (uid));

INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (3, 4, 'hello1', '2018-11-04 14:53:27');
INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (5, 4, 'hello2', '2018-11-05 14:53:27');
INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (6, 4, 'hello3', '2018-11-06 14:53:27');
INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (3, 5, 'hello4', '2018-11-07 14:53:27');
INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (3, 6, 'hello5', '2018-11-08 14:53:27');
INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (3, 7, 'hello6', '2018-11-09 14:53:27');
INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (4, 4, 'hello7', '2018-11-04 14:53:27');
INSERT INTO Comments (nid,uid, ctext, ctimestamp)
VALUES (5, 8, 'hello8', '2018-11-04 14:53:27');





CREATE TABLE Tag (
  tid INT NOT NULL AUTO_INCREMENT,
  ttext VARCHAR(45) NOT NULL,
  PRIMARY KEY (tid));

INSERT INTO Tag (ttext)
VALUES ("#food");
INSERT INTO Tag (ttext)
VALUES ("#korean");
INSERT INTO Tag (ttext)
VALUES ("#spicy");
INSERT INTO Tag (ttext)
VALUES ("#movies");
INSERT INTO Tag (ttext)
VALUES ("#game_of_thrones");
INSERT INTO Tag (ttext)
VALUES ("#FIFA");
INSERT INTO Tag (ttext)
VALUES ("#Call_OF_DUTY");
INSERT INTO Tag (ttext)
VALUES ("#GOD_of_war");
INSERT INTO Tag (ttext)
VALUES ("#chess");
INSERT INTO Tag (ttext)
VALUES ("#politics");
INSERT INTO Tag (ttext)
VALUES ("#NYC");
INSERT INTO Tag (ttext)
VALUES ("#me");
INSERT INTO Tag (ttext)
VALUES ("#drake");
INSERT INTO Tag (ttext)
VALUES ("#meek_mills");

CREATE TABLE Tag_In_Note (
  nid INT NOT NULL,
  tid INT NOT NULL,
  PRIMARY KEY (nid,tid),
  CONSTRAINT nid2_fk FOREIGN KEY (nid) REFERENCES Note (nid),
  CONSTRAINT tid_fk FOREIGN KEY (tid) REFERENCES Tag (tid));


INSERT INTO Tag_In_Note VALUES (1,3);
INSERT INTO Tag_In_Note VALUES (1,4);
INSERT INTO Tag_In_Note VALUES (1,1);
INSERT INTO Tag_In_Note VALUES (1,2);

INSERT INTO Tag_In_Note VALUES (2,3);
INSERT INTO Tag_In_Note VALUES (2,1);
INSERT INTO Tag_In_Note VALUES (2,2);

INSERT INTO Tag_In_Note VALUES (4,3);
INSERT INTO Tag_In_Note VALUES (2,8);

INSERT INTO Tag_In_Note VALUES (4,1);
INSERT INTO Tag_In_Note VALUES (4,2);


 CREATE TABLE State (
  sid INT NOT NULL AUTO_INCREMENT,
  uid INT NOT NULL,
  sname VARCHAR(45) NOT NULL,
  isActive VARCHAR(45) NOT NULL,
  PRIMARY KEY (sid),
  CONSTRAINT uid3_fk FOREIGN KEY (uid) REFERENCES Users (uid));

INSERT INTO State (uid, sname, isActive)
VALUES (1,"at home", "True");
INSERT INTO State (uid, sname, isActive)
VALUES (2,"at home", "False");
INSERT INTO State (uid, sname, isActive)
VALUES (3,"at home", "True");
INSERT INTO State (uid, sname, isActive)
VALUES (4,"drinking at the bar", "True");
INSERT INTO State (uid, sname, isActive)
VALUES (5,"sheesha at home", "True");
INSERT INTO State (uid, sname, isActive)
VALUES (6,"at work", "True");
INSERT INTO State (uid, sname, isActive)
VALUES (7,"at work", "True");
INSERT INTO State (uid, sname, isActive)
VALUES (8,"at work", "False");
INSERT INTO State (uid, sname, isActive)
VALUES (9,"at work", "True");

 CREATE TABLE Filters (
  fid INT NOT NULL AUTO_INCREMENT,
  uid INT NOT NULL,
  tid INT NOT NULL,
  sid INT,
  sched_id INT NOT NULL,
  filter_privacy VARCHAR(45) NOT NULL,
  fname VARCHAR(45) NOT NULL,
  radius FLOAT( 10, 6 ) NOT NULL,
  latitude FLOAT( 10, 6 ) NOT NULL,
  longitude FLOAT( 10, 6 ) NOT NULL,
  PRIMARY KEY (fid),
  CONSTRAINT uid4_fk FOREIGN KEY (uid) REFERENCES Users (uid),
  CONSTRAINT tid1_fk FOREIGN KEY (tid) REFERENCES Tag (tid),
  CONSTRAINT sid_fk FOREIGN KEY (sid) REFERENCES State (sid));

INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy, radius, latitude, longitude)
VALUES (1,3, 1, 21, 'f1', "friends", 10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (2,4, 2, 22,'f2', "public",10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (3,5, 3, 23,'f3', "public",10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (4,1, 4, 24,'f4', "public",10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (5,2, 5, 25,'f5', "public",10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (6,6, 6, 26,'f6', "public",10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (7,8, 7, 27,'f7', "self",10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (8,9, 8, 28,'f8', "friends",10.1,  40.728580, -73.995520);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (9,10, 9, 29,'f9', "friends",10.1, 43.729513, -74.996460);
INSERT INTO Filters (uid, tid, sid, sched_id, fname, filter_privacy,radius, latitude, longitude)
VALUES (10,11, null, 30,'f10', "friends",10.1,  40.728580, -73.995520);
