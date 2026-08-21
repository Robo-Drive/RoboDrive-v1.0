CREATE DATABASE IF NOT EXISTS robo_drive;

USE robo_drive;


CREATE TABLE IF NOT EXISTS equipe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(100) NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  nome_usuario VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(1000) NOT NULL,
  biografia VARCHAR(2000),
  imagem VARCHAR(1000),
  regra ENUM('admin','usuario') NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  usuario_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao VARCHAR(2000) NOT NULL,
  visibilidade ENUM('privado','equipe','publico') DEFAULT 'privado',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  usuario_id INT NOT NULL,
  categoria_id INT NOT NULL,
  equipe_id INT,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  FOREIGN KEY (categoria_id) REFERENCES categoria(id),
  FOREIGN KEY (equipe_id) REFERENCES equipe(id)
);

CREATE TABLE IF NOT EXISTS postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  visibilidade ENUM('equipe','publico') DEFAULT 'publico',
  usuario_id INT NOT NULL,
  equipe_id INT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  FOREIGN KEY (equipe_id) REFERENCES equipe(id)
);

CREATE TABLE IF NOT EXISTS comentario_postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  postagem_forum_id INT NOT NULL,
  usuario_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (postagem_forum_id) REFERENCES postagem_forum(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS comentario_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao VARCHAR(2000) NOT NULL,
  imagem TEXT,
  usuario_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS imagem_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(255) NOT NULL,
  projeto_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE IF NOT EXISTS codigo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(255) NOT NULL,
  descricao VARCHAR(255),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS projeto_versao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  versao INT NOT NULL,  
  descricao_alteracao TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS codigo_versao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo_id INT NOT NULL,
  projeto_versao_id INT NOT NULL, 
  FOREIGN KEY (projeto_versao_id) REFERENCES projeto_versao(id),
  FOREIGN KEY (codigo_id) REFERENCES codigo(id)
);

CREATE TABLE IF NOT EXISTS projeto_versao_componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quantidade INT NOT NULL,
  componente_id INT NOT NULL,
  projeto_versao_id INT NOT NULL,
  FOREIGN KEY (componente_id) REFERENCES componente(id),
  FOREIGN KEY (projeto_versao_id) REFERENCES projeto_versao(id)
);

CREATE TABLE IF NOT EXISTS projeto_componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quantidade INT NOT NULL,
  projeto_id INT NOT NULL,
  componente_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (componente_id) REFERENCES componente(id)
);

CREATE TABLE IF NOT EXISTS projeto_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  papel ENUM('coordenador','participante') NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS equipe_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipe_id INT NOT NULL,
  usuario_id INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  papel ENUM('coordenador','participante') NOT NULL,
  FOREIGN KEY (equipe_id) REFERENCES equipe(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- --------------------------------------------------
-- DADOS DE TESTE
-- --------------------------------------------------

INSERT INTO usuario
(nome, nome_usuario, email, senha, biografia, imagem, regra, status)
VALUES
('Walmonn Eduardo Barbosa Ramalho da Silva',
 'WalmonnEduardo',
 'walmonn.eduardo.tds2023@gmail.com',
 '$2y$12$/E551s2RVpWQgvvUnbq4E.ZZvKoaE2V1cNwPp2aTJokiCd6lle3/a',
 'Desenvolvedor e entusiasta de robótica.',
 '/srv/http/RoboDrive-v1.0/app/config/../../store/users/user-1/img/693bba41ae823701b63aedc23d89a71a.jpeg',
 'admin',
 TRUE),

('Guilherme Canever Wernke',
 'guilherme',
 'guilherme.wernke.tds2023@gmail.com',
 '$2y$12$pIqRCJLzv2Ia0jGlzk9VSOTAIK4YZk4/UB0Zs/3gjUerizr0DSTpW',
 'Professor de robótica.',
 '/srv/http/RoboDrive-v1.0/app/config/../../store/users/user-2/img/4b4c5d887ba820581966f5215c2d7b8e.jpg',
 'admin',
 TRUE),

('Petrus Mito de Souza',
 'petrus',
 'petrus.souza.tds2023@gmail.com',
 '$2y$12$ECX23EPvPazaiSUN2asdwOLf0carz.YsWft/4Y93ziODDBmoq08PW',
 'Especialista em eletrônica.',
 '/srv/http/RoboDrive-v1.0/app/config/../../store/users/user-3/img/f79dc89b68ade760f62dd93b3d8479a1.jpg',
 'admin',
 TRUE);

INSERT INTO categoria (nome, usuario_id) VALUES
('Meus projetos', 1),
('Meus projetos', 2),
('Meus projetos', 3);
