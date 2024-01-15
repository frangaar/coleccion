drop database if exists magic;

create database magic;

use magic;

create table tipo_carta(
id int auto_increment,
nombre varchar(50) not null,
PRIMARY KEY(id)
);

create table expansiones(
id int auto_increment,
nombreExpansion varchar(255) not null,
PRIMARY KEY(id));

create table cartas(
id int auto_increment,
idExpansion int,
ataque int,
defensa int,
idTipoCarta int,
imagen varchar(50),
PRIMARY KEY(id),
foreign key (idTipoCarta) references tipo_carta(id) ON UPDATE CASCADE,
foreign key (idExpansion) references expansiones(id) ON UPDATE CASCADE);


create table tipo_mana(
id int,
nombre varchar(30) UNIQUE not null,
PRIMARY KEY(id));


create table cartas_tipomana(
idCarta int,
idTipoMana int,
cantidadMana int not null,
PRIMARY KEY(idCarta,idTipoMana),
foreign key (idCarta) references cartas(id) ON DELETE CASCADE,
foreign key (idTipoMana) references tipo_mana(id) ON DELETE CASCADE);


INSERT INTO `expansiones` VALUES (1,'El señor de los anillos'),(2,'Juego de tronos');
INSERT INTO `tipo_carta` VALUES (1,'Tierras'),(2,'Criaturas'),(3,'Encantamientos'),(4,'Artefactos'),(5,'Instantáneos'),(6,'Conjuros');
INSERT INTO `tipo_mana` VALUES (2,'Azul'),(1,'Blanco'),(6,'Incoloro'),(3,'Negro'),(4,'Rojo'),(5,'Verde');
INSERT INTO `cartas` VALUES (6,1,6,6,2,'img/1.jpg'),(8,1,0,0,1,'img/2.jpg'),(9,2,0,0,1,'img/3.jpg'),(10,1,3,3,2,'img/4.jpg');
update cartas set idExpansion=1 where id=1;
INSERT INTO `cartas_tipomana` VALUES (6,1,1),(6,2,2),(6,3,2),(6,5,2),(8,1,1),(8,3,1),(8,4,1),(9,2,1),(9,5,3),(9,6,3),(10,1,1),(10,4,3);