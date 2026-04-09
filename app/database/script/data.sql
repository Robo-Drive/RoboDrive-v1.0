CREATE DATABASE IF NOT EXISTS robo_drive;

USE robo_drive;

CREATE TABLE IF NOT EXISTS equipe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    identidade VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    imagem VARCHAR(255),
    regra ENUM('admin','professor','estudante') NOT NULL,
    equipe_id INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (equipe_id) REFERENCES equipe(id)
);

CREATE TABLE IF NOT EXISTS projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    equipe_id INT NOT NULL,
    visibilidade ENUM('privado','equipe','publico') DEFAULT 'privado',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (equipe_id) REFERENCES equipe(id)
);

CREATE TABLE IF NOT EXISTS codigo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caminho VARCHAR(255) NOT NULL,
    descricao VARCHAR(255),
    projeto_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE IF NOT EXISTS componente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255)
    imagem VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS projeto_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NOT NULL,
    usuario_id INT NOT NULL,

    FOREIGN KEY (projeto_id) REFERENCES projeto(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS projeto_componente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quantidade INT NOT NULL,
    projeto_id INT NOT NULL,
    componente_id INT NOT NULL,

    FOREIGN KEY (projeto_id) REFERENCES projeto(id),
    FOREIGN KEY (componente_id) REFERENCES componente(id)
);

CREATE TABLE IF NOT EXISTS imagem_projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caminho INT NOT NULL,
    projeto_id INT NOT NULL,
    
    FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE IF NOT EXISTS comentario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo INT NOT NULL,
    robo_id INT NOT NULL,

    FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);