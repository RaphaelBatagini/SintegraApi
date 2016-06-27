create database circuitorh;

use circuitorh;

CREATE TABLE sintegra (
    id INT(11),
    idusuario INT(11),
    cnpj VARCHAR(14),
    resultado_json TEXT
);

CREATE TABLE users (
    id INT(11),
    name VARCHAR(150),
    email VARCHAR(150),
    password VARCHAR(60),
    remember_token VARCHAR(100),
    created_at DATETIME,
    updated_at DATETIME
);