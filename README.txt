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

create table groups(idGroup int primary key auto_increment, nameGroup varchar(20), schedule enum('9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'), creationDate timestamp default current_timestamp, fkCareer int, foreign key(fkCareer) references careers(idCareer) on update cascade on delete restrict);

create or replace table persons(idPerson int primary key auto_increment, namePerson varchar(60), lastNamePerson varchar(100), genderPerson enum('M','F'), birthDatePerson date, creationDate timestamp default current_timestamp);

create or replace table users(idUser int primary key auto_increment, emailUser varchar(160), passwordUser varchar(160), typeUser enum('Administrator','Student','Teacher'), addressUser varchar(160), phoneUser varchar(15), creationDate timestamp default current_timestamp, fkPerson int, foreign key(fkPerson) references persons(idPerson) on update cascade on delete cascade);

create or replace table assesors(idAssesor int primary key auto_increment, statusAssesor enum('Active','Inactive') default 'Active', creationDate timestamp default current_timestamp, fkPerson int, foreign key(fkPerson) references persons(idPerson) on update cascade on delete cascade);


create table students(idStudent int primary key auto_increment, numberRegister int, startDateCourseStudent date, creationDate timestamp default current_timestamp, statusStudent enum('Activo','Baja Temporal','Baja Definitiva','Graduado') default 'Activo', weekRegistrationStudent varchar(20), observations text, fkPerson int, fkAssesor int, fkGroup int, foreign key(fkPerson) references persons(idPerson) on update cascade on delete cascade, foreign key(fkAssesor) references assesors(idAssesor) on update cascade on delete restrict, foreign key(fkGroup) references groups(idGroup) on update cascade on delete restrict);

create table teachers(idTeacher int primary key auto_increment, statusTeacher enum('Active','Inactive') default 'Active', creationDateTeacher timestamp default current_timestamp, fkPerson int, fkGroup int, foreign key(fkPerson) references persons(idPerson) on update cascade on delete cascade, foreign key(fkGroup) references groups(idGroup) on update cascade on delete restrict);


create table attendances(idAttendance int primary key auto_increment, WeekAttendance date, statusAttendance enum('In Progress','Finalized') default 'In Progress', currentDateAttendance timestamp default current_timestamp, departureTime timestamp default current_timestamp on update current_timestamp, observations text, fkPerson int, foreign key(fkPerson) references persons(idPerson) on update cascade on delete restrict);














