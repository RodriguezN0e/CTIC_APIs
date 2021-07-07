create database CTICDB;

use CTICDB;


CREATE TABLE tkeys (
       id INT(11) NOT NULL AUTO_INCREMENT,
       user_id INT(11) NOT NULL,
       ckey VARCHAR(40) NOT NULL,
       level INT(2) NOT NULL,
       ignore_limits TINYINT(1) NOT NULL DEFAULT '0',
       is_private_key TINYINT(1)  NOT NULL DEFAULT '0',
       ip_addresses TEXT NULL DEFAULT NULL,
       date_created INT(11) NOT NULL,
       PRIMARY KEY (`id`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


insert into tkeys(user_id,ckey,level,date_created) values(1,'QWERTY',1,123456789);


create or replace table careers(idCareer int primary key auto_increment, nameCareer varchar(40), duration varchar(40), inscription decimal(19,2), tuition decimal(19,2), creationDate timestamp default current_timestamp);

create or replace table groups(idGroup int primary key auto_increment, nameGroup varchar(20), schedule enum('9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'), creationDate timestamp default current_timestamp, dayScheduleGroup enum('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'));

create table usersStuTea(idUser int primary key auto_increment, nameUser varchar(20), lastNameUser varchar(50), genderUser enum('M','F'), birthDatePerson date, emailUser varchar(160), passwordUser varchar(160), typeUser enum('Administrator','Student','Teacher'), addressUser varchar(160), phoneUser varchar(30), statusAssesorTeacher enum ('Active','Inactive') default 'Active', numberRegister varchar(10), startDateCourseStudent date, statusStudent enum('Activo','Baja Temporal','Baja Definitiva','Graduado') default 'Activo', weekRegistrationStudent timestamp default current_timestamp, observations text, fkCareer int, foreign key(fkCareer) references careers(idCareer) on update cascade on delete restrict);

create table assignmentUserGroup(idAssignment int primary key auto_increment, fkGroup int, fkUser int, foreign key(fkGroup) references groups(idGroup) on update cascade on delete restrict, foreign key(fkUser) references usersStuTea(idUser) on update cascade on delete restrict)

create table attendances(idAttendance int primary key auto_increment, WeekAttendance date, statusAttendance enum('In Progress','Finalized') default 'In Progress', currentDateAttendance timestamp default current_timestamp, departureTime timestamp default current_timestamp on update current_timestamp, observations text, fkPerson int, foreign key(fkPerson) references persons(idPerson) on update cascade on delete restrict);














