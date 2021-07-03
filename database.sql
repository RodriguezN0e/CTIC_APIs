create database CTICDB;

use CTICDB; 

create table career(idCareer int primary key auto_increment, duration varchar(40), inscription enum('1200','2000'), tuition enum('200','2500'), creationDate timestamp default current_timestamp);



create database environmentalmonit;

use environmentalmonit;

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


create table system(idSystem int primary key auto_increment, nameSystem varchar(30) not null);

create or replace table station(idStation int primary key auto_increment, nameStation varchar(30) not null, latitudeStation varchar(20) not null, longitudeStation varchar(20) not null, fkSystem int, foreign key(fkSystem) references system(idSystem) ON UPDATE RESTRICT ON DELETE RESTRICT);

create table sensor(idSensor int primary key auto_increment, nameSensor varchar(30) not null, fkStation int, foreign key(fkStation) references station(idStation) ON UPDATE RESTRICT ON DELETE RESTRICT);

create table measure(idMeasure int primary key auto_increment, dateMeasure datetime(6) not null, valueMeasure varchar(10) not null, fkSensor int, foreign key(fkSensor) references sensor(idSensor) ON UPDATE RESTRICT ON DELETE RESTRICT);


CREATE OR REPLACE VIEW measuresByStation AS select
station.idStation AS idStation,
station.nameStation as nameStation,
sensor.idSensor as idSensor,
sensor.nameSensor as nameSensor,
measure.idMeasure as idMeasure,
measure.dateMeasure as dateMeasure,
measure.valueMeasure as valueMeasure
from (`station` join `sensor` join `measure`)  
where ((`sensor`.`fkStation` = `station`.`idStation`) and (`measure`.`fkSensor` = `sensor`.`idSensor`));


create table user(idUser int primary key auto_increment, nameUser varchar(60), emailUser varchar(120), passwordUser varchar(180), typeUser enum('Cliente','Administrador') default 'Cliente');


CREATE OR REPLACE VIEW systemStation AS select
system.idSystem AS idSystem,
system.nameSystem as nameSystem,
station.idStation AS idStation,
station.nameStation as nameStation,
station.latitudeStation AS latitudeStation,
station.longitudeStation as longitudeStation
from (`system` join `station`)  
where ((`station`.`fkSystem` = `system`.`idSystem`));


CREATE OR REPLACE VIEW stationSensor AS select
station.idStation AS idStation,
station.nameStation as nameStation,
sensor.idSensor AS idSensor,
sensor.nameSensor as nameSensor
from (`station` join `sensor`)  
where ((`sensor`.`fkStation` = `station`.`idStation`));






