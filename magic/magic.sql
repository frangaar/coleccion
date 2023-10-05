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
nombre varchar(30) not null,
descripcion varchar(255),
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


insert into tipo_carta values (1,"Tierras");
insert into tipo_carta values (2,"Criaturas");
insert into tipo_carta values (3,"Encantamientos");
insert into tipo_carta values (4,"Artefactos");
insert into tipo_carta values (5,"Instantáneos");
insert into tipo_carta values (6,"Conjuros");

insert into tipo_mana values (1,"Blanco");
insert into tipo_mana values (2,"Azul");
insert into tipo_mana values (3,"Negro");
insert into tipo_mana values (4,"Rojo");
insert into tipo_mana values (5,"Verde");
insert into tipo_mana values (6,"Incoloro");

insert into expansiones values (null,"El señor de los anillos");
insert into expansiones values (null,"Juego de tronos");


insert into cartas(id,nombre,descripcion,idExpansion,ataque,defensa,idTipoCarta,imagen) values (null,"Intercambiar unas palabras","Victimizar 1. (Al lanzar este hechizo, puedes sacrificar una criatura con fuerza de 1 o más. Cuando lo hagas, copia este hechizo.)
Mira las dos primeras cartas de tu biblioteca. Pon una de ellas en tu mano y la otra en el fondo de tu biblioteca.",1,6,6,3,"img/2.jpg");

update cartas set idExpansion=1 where id=1;

create table cartas_tipomana(
idCarta int,
idTipoMana int,
cantidadMana int not null,
PRIMARY KEY(idCarta,idTipoMana),
foreign key (idCarta) references cartas(id) ON DELETE CASCADE,
foreign key (idTipoMana) references tipo_mana(id) ON DELETE CASCADE);



insert into cartas_tipomana values (1,2,1);
insert into cartas_tipomana values (1,3,4);