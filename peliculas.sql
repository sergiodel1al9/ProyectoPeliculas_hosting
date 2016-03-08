drop database if exists peliculas;
create database peliculas;

use peliculas;

CREATE TABLE genero
(
id_genero INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
tipo varchar(255) not null
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE artista
(
id_artista INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nombre varchar(255) not null,
apellidos varchar(255) not null
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE pelicula
(
id_pelicula INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nombre varchar(255) not null,
anyo int not null,
id_genero int not null,
sinopsis varchar(500) not null,
duracion int not null,
FOREIGN KEY (id_genero) REFERENCES genero(id_genero) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE pelicula_actor
(
id_pelicula_actor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
id_pelicula int not null,
id_artista int not null,
FOREIGN KEY (id_artista) REFERENCES artista(id_artista) ON UPDATE CASCADE ON DELETE CASCADE,
FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE pelicula_director
(
id_pelicula_director INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
id_pelicula int not null,
id_artista int not null,
FOREIGN KEY (id_artista) REFERENCES artista(id_artista) ON UPDATE CASCADE ON DELETE CASCADE,
FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE pelicula_guionista
(
id_pelicula_guionista INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
id_pelicula int not null,
id_artista int not null,
FOREIGN KEY (id_artista) REFERENCES artista(id_artista) ON UPDATE CASCADE ON DELETE CASCADE,
FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE usuarios (
  usuario varchar(255) NOT NULL PRIMARY KEY COLLATE utf8_spanish_ci,
  contrasena varchar(255) NOT NULL COLLATE utf8_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO usuarios VALUES('sergio',MD5('sergio'));
INSERT INTO usuarios VALUES('dwes',MD5('abc123.'));