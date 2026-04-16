CREATE DATABASE IF NOT EXISTS robo_drive;

USE robo_drive;

CREATE TABLE equipe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_equipe VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(100) NOT NULL
);

CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  imagem VARCHAR(255),
  regra ENUM('admin','professor','estudante') NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (campus_id) REFERENCES campus(id)
);

CREATE TABLE projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  campus_id INT NOT NULL,
  visibilidade ENUM('privado','equipe','publico') DEFAULT 'privado',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (campus_id) REFERENCES campus(id)
);

CREATE TABLE postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  visibilidade ENUM('equipe','publico') DEFAULT 'equipe',
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE comentario_postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  postagem_forum_id INT NOT NULL,
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (postagem_forum_id) REFERENCES postagem_forum(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE comentario_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao VARCHAR(255),
  imagem VARCHAR(255)
);

CREATE TABLE imagem_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(255) NOT NULL,
  projeto_id INT NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE codigo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(255) NOT NULL,
  descricao VARCHAR(255),
  projeto_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE projeto_componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quantidade INT NOT NULL,
  projeto_id INT NOT NULL,
  componente_id INT NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (componente_id) REFERENCES componente(id)
);

CREATE TABLE projeto_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE equipe_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipe_id INT NOT NULL,
  usuario_id INT NOT NULL,
  tipo ENUM('coodenador','participante') NOT NULL,
  FOREIGN KEY (equipe_id) REFERENCES equipe(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);