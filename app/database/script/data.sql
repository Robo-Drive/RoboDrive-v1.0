CREATE DATABASE IF NOT EXISTS robo_drive;

USE robo_drive;

CREATE TABLE IF NOT EXISTS equipe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_equipe VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  imagem VARCHAR(255),
  regra ENUM('admin','usuario') NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  visibilidade ENUM('privado','equipe','publico') DEFAULT 'privado',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  visibilidade ENUM('equipe','publico') DEFAULT 'equipe',
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS comentario_postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  postagem_forum_id INT NOT NULL,
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (postagem_forum_id) REFERENCES postagem_forum(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS comentario_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo TEXT NOT NULL,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao VARCHAR(255),
  imagem VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS imagem_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(255) NOT NULL,
  projeto_id INT NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE IF NOT EXISTS codigo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(255) NOT NULL,
  descricao VARCHAR(255),
  projeto_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE IF NOT EXISTS projeto_componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quantidade INT NOT NULL,
  projeto_id INT NOT NULL,
  componente_id INT NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (componente_id) REFERENCES componente(id)
);

CREATE TABLE IF NOT EXISTS projeto_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS equipe_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipe_id INT NOT NULL,
  usuario_id INT NOT NULL,
  tipo ENUM('coodenador','participante') NOT NULL,
  FOREIGN KEY (equipe_id) REFERENCES equipe(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS seguidores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  usuario_id_segue INT NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  FOREIGN KEY (usuario_id_segue) REFERENCES usuario(id)
);

USE robo_drive;

-- EQUIPE
INSERT INTO equipe (nome_equipe, senha) VALUES
('Equipe Alpha', '123'),
('Equipe Beta', '123'),
('Equipe Gamma', '123');

-- USUARIOS
INSERT INTO usuario (nome, email, senha, imagem, regra) VALUES
('Eduardo', 'eduardo@email.com', '123', 'eduardo.png', 'admin'),
('Maria', 'maria@email.com', '123', 'maria.png', 'usuario'),
('João', 'joao@email.com', '123', 'joao.png', 'usuario'),
('Ana', 'ana@email.com', '123', 'ana.png', 'usuario');

-- PROJETOS
INSERT INTO projeto (nome, visibilidade) VALUES
('Robô Seguidor de Linha', 'publico'),
('Braço Robótico', 'equipe'),
('Drone Arduino', 'privado');

-- POSTAGENS
INSERT INTO postagem_forum (conteudo, visibilidade, usuario_id) VALUES
('Como melhorar PID no robô?', 'publico', 1),
('Alguém tem código para servo?', 'equipe', 2);

-- COMENTÁRIOS DE POSTAGEM
INSERT INTO comentario_postagem_forum (conteudo, postagem_forum_id, usuario_id) VALUES
('Tenta ajustar o Kp primeiro', 1, 2),
('Tenho sim, depois te mando', 2, 3);

-- COMENTÁRIOS DE PROJETO
INSERT INTO comentario_projeto (conteudo, projeto_id, usuario_id) VALUES
('Projeto muito bom!', 1, 2),
('Precisa melhorar a estrutura', 2, 3);

-- COMPONENTES
INSERT INTO componente (nome, descricao, imagem) VALUES
('Arduino Uno', 'Microcontrolador', 'arduino.png'),
('Sensor Ultrassônico', 'Mede distância', 'ultra.png'),
('Servo Motor', 'Movimento angular', 'servo.png');

-- IMAGENS PROJETO
INSERT INTO imagem_projeto (caminho, projeto_id) VALUES
('linha1.png', 1),
('braco1.png', 2);

-- CÓDIGOS
INSERT INTO codigo (caminho, descricao, projeto_id) VALUES
('codigo1.ino', 'Controle do robô', 1),
('codigo2.ino', 'Movimento do braço', 2);

-- PROJETO COMPONENTE
INSERT INTO projeto_componente (quantidade, projeto_id, componente_id) VALUES
(2, 1, 1),
(1, 1, 2),
(3, 2, 3);

-- PROJETO USUARIO
INSERT INTO projeto_usuario (projeto_id, usuario_id) VALUES
(1, 1),
(1, 2),
(2, 3);

-- EQUIPE USUARIO
INSERT INTO equipe_usuario (equipe_id, usuario_id, tipo) VALUES
(1, 1, 'coodenador'),
(1, 2, 'participante'),
(2, 3, 'coodenador'),
(3, 4, 'participante');

-- SEGUIDORES
INSERT INTO seguidores (usuario_id, usuario_id_segue) VALUES
(1, 2),
(2, 3),
(3, 1);