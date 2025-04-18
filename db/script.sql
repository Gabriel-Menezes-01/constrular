CREATE DATABASE construtora;

USE constrular;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    conteudo TEXT,
    data DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE conteudo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texto TEXT,
    imagem VARCHAR(255)
);