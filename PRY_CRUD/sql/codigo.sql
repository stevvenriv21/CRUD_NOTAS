CREATE TABLE notas (
	id int AUTO_INCREMENT PRIMARY KEY,
    usuario_id int NOT null,
    materia_id int not null,
    n1 decimal(5,2) not null,
    n2 decimal(5,2) not null,
    n3 decimal(5,2) not null,
    promedio decimal(5,2) not null,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE
);

CREATE TABLE materias (
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(100) not null,
    nrc varchar(10) not null
);

CREATE TABLE usuarios (
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(255) not null,
    email varchar(255) not null,
    edad int
);