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
  descricao VARCHAR(100) NOT NULL,
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
  tipo ENUM('coodenador','participante') NOT NULL,
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

INSERT INTO equipe (nome_equipe, senha) VALUES
('Equipe Alpha', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2'),
('Equipe Beta',  '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2'),
('Equipe Gamma', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2');

-- =====================
-- USUARIOS (senha: admin12345)
-- =====================
INSERT INTO usuario (nome, email, senha, imagem, regra) VALUES
('Monkey D. Luffy', 'luffy@gmail.com',
'$2y$12$twxVhhtHCFpYcOk8W02lq.PP/4hxFB9Urf9sV2lKPP4/JgF.Nkd16',
'https://cdn.pixabay.com/photo/2021/08/04/13/06/anime-6523770_960_720.png', 'admin'),

('Naruto Uzumaki', 'naruto@gmail.com',
'$2y$12$twxVhhtHCFpYcOk8W02lq.PP/4hxFB9Urf9sV2lKPP4/JgF.Nkd16',
'https://cdn.pixabay.com/photo/2023/03/27/14/31/anime-7884390_960_720.png', 'admin'),

('Sakura Haruno', 'sakura@gmail.com',
'$2y$12$twxVhhtHCFpYcOk8W02lq.PP/4hxFB9Urf9sV2lKPP4/JgF.Nkd16',
'https://cdn.pixabay.com/photo/2022/10/16/17/24/anime-7525455_960_720.png', 'usuario'),

('Goku', 'goku@gmail.com',
'$2y$12$twxVhhtHCFpYcOk8W02lq.PP/4hxFB9Urf9sV2lKPP4/JgF.Nkd16',
'https://cdn.pixabay.com/photo/2023/06/20/17/17/anime-8076614_960_720.png', 'usuario');

-- =====================
-- PROJETOS
-- =====================
INSERT INTO projeto (nome, descricao, visibilidade) VALUES
('Robô Seguidor de Linha', 'Robô que segue uma linha usando sensores', 'publico'),
('Braço Robótico', 'Braço mecânico controlado por servo motores', 'equipe'),
('Drone Arduino', 'Drone controlado por Arduino com sensores', 'privado');

-- =====================
-- COMPONENTES
-- =====================
INSERT INTO componente (nome, descricao, imagem) VALUES
('Arduino Uno', 'Microcontrolador',
'https://upload.wikimedia.org/wikipedia/commons/3/38/Arduino_Uno_-_R3.jpg'),

('Sensor Ultrassônico', 'Mede distância',
'https://upload.wikimedia.org/wikipedia/commons/0/0b/HCSR04.jpg'),

('Servo Motor', 'Movimento angular',
'https://upload.wikimedia.org/wikipedia/commons/3/3e/Servo.jpg');

-- =====================
-- RESTANTE
-- =====================
INSERT INTO postagem_forum (conteudo, visibilidade, usuario_id) VALUES
('Como melhorar PID no robô?', 'publico', 1),
('Alguém tem código para servo?', 'equipe', 2);

INSERT INTO comentario_postagem_forum (conteudo, postagem_forum_id, usuario_id) VALUES
('Tenta ajustar o Kp primeiro', 1, 2),
('Tenho sim, depois te mando', 2, 3);

INSERT INTO comentario_projeto (conteudo, projeto_id, usuario_id) VALUES
('Projeto muito bom!', 1, 2),
('Precisa melhorar a estrutura', 2, 3);

INSERT INTO imagem_projeto (caminho, projeto_id) VALUES
('linha1.png', 1),
('braco1.png', 2);

INSERT INTO codigo (caminho, descricao, projeto_id) VALUES
('codigo1.ino', 'Controle do robô', 1),
('codigo2.ino', 'Movimento do braço', 2);

INSERT INTO projeto_componente (quantidade, projeto_id, componente_id) VALUES
(2, 1, 1),
(1, 1, 2),
(3, 2, 3);

INSERT INTO projeto_usuario (projeto_id, usuario_id, tipo) VALUES
(1, 1, "coordenador"),
(1, 2, "participante"),
(2, 3, "coordenador"),
(3, 4, "participante");

INSERT INTO equipe_usuario (equipe_id, usuario_id, tipo) VALUES
(1, 1, 'coordenador'),
(1, 2, 'participante'),
(2, 3, 'coordenador'),
(3, 4, 'participante');

INSERT INTO seguidores (usuario_id, usuario_id_segue) VALUES
(1, 2),
(2, 3),
(3, 1),
(4, 1);