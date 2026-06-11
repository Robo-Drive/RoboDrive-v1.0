CREATE DATABASE IF NOT EXISTS robo_drive;

USE robo_drive;

CREATE TABLE IF NOT EXISTS equipe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  nome_usuario VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(1000) NOT NULL,
  biografia VARCHAR(100),
  imagem TEXT,
  regra ENUM('admin','usuario') NOT NULL,
  status BOOLEAN NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  usuario_id INT NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao VARCHAR(100) NOT NULL,
  visibilidade ENUM('privado','equipe','publico') DEFAULT 'privado',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  usuario_id INT NOT NULL,
  categoria_id INT NOT NULL,
  equipe_id INT,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  FOREIGN KEY (categoria_id) REFERENCES categoria(id),
  FOREIGN KEY (equipe_id) REFERENCES equipe(id)
);

CREATE TABLE IF NOT EXISTS postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo VARCHAR(100) NOT NULL,
  visibilidade ENUM('equipe','publico') DEFAULT 'equipe',
  usuario_id INT NOT NULL,
  equipe_id INT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  FOREIGN KEY (equipe_id) REFERENCES equipe(id)
);

CREATE TABLE IF NOT EXISTS comentario_postagem_forum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo VARCHAR(100) NOT NULL,
  postagem_forum_id INT NOT NULL,
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (postagem_forum_id) REFERENCES postagem_forum(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS comentario_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  conteudo VARCHAR(100) NOT NULL,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS componente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao VARCHAR(100),
  imagem TEXT
);

CREATE TABLE IF NOT EXISTS imagem_projeto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(100) NOT NULL,
  projeto_id INT NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

CREATE TABLE IF NOT EXISTS codigo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caminho VARCHAR(100) NOT NULL,
  descricao VARCHAR(100),
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
  tipo ENUM('coordenador','participante') NOT NULL,
  FOREIGN KEY (projeto_id) REFERENCES projeto(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS equipe_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipe_id INT NOT NULL,
  usuario_id INT NOT NULL,
  categoria ENUM('coordenador','participante') NOT NULL,
  FOREIGN KEY (equipe_id) REFERENCES equipe(id),
  FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

----------------------------------------------------
-- DADOS
----------------------------------------------------

INSERT INTO equipe (nome, senha) VALUES
('Equipe Alpha', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2'),
('Equipe Beta', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2'),
('Equipe Gamma', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2');

INSERT INTO usuario
(nome, nome_usuario, email, senha, imagem, regra, status)
VALUES
(
'Walmonn Eduardo Barbosa Ramalho da Silva',
'WalmonnEduardo',
'walmonn.eduardo.tds2023@gmail.com',
'$2y$12$/E551s2RVpWQgvvUnbq4E.ZZvKoaE2V1cNwPp2aTJokiCd6lle3/a',
'https://imgs.search.brave.com/NqH3jeCkzn-2YsnzIUEdiXq8UUKAkid746LYzGWHRTA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFnZS5nZWVrc2hpcC5jb20uYnIvMHo4dF96QkZNYWR6djhheTFySHBFajJQX2tRPS8yMjAweDAvc21hcnQvZmlsdGVyczpzdHJpcF9pY2MoKTpmb3JtYXQod2VicCkvaHVsbC5nZWVrc2hpcC5jb20uYnIvd3AtY29udGVudC91cGxvYWRzLzIwMjYvMDMvU2FpLWRyLXN0b25lLTEuanBn',
'admin',
1
),
(
'Guilherme Canever Wernke',
'guilherme',
'guilherme.wernke.tds2023@gmail.com',
'$2y$12$pIqRCJLzv2Ia0jGlzk9VSOTAIK4YZk4/UB0Zs/3gjUerizr0DSTpW',
'https://imgs.search.brave.com/Z74bF9aCDjhJpKYzmZityTXB9jhz6CS0DY4V87IsQiY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly80a3dhbGxwYXBlcnMuY29tL2ltYWdlcy93YWxscy90aHVtYnMvMjU0OTkuanBn',
'admin',
1
),
(
'Petrus Mito de Souza',
'petrus',
'petrus.souza.tds2023@gmail.com',
'$2y$12$ECX23EPvPazaiSUN2asdwOLf0carz.YsWft/4Y93ziODDBmoq08PW',
'https://imgs.search.brave.com/doD7wVUtS-TJ0EGe1XCSmit08ijnmpgGGYdVIGYkOwE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93YWxscGFwZXIuZG9nL3RodW1ibmFpbC81NDQyMDI5LnBuZw',
'admin',
1
),
(
'teste',
'teste',
'teste@gmail.com',
'$2y$12$twxVhhtHCFpYcOk8W02lq.PP/4hxFB9Urf9sV2lKPP4/JgF.Nkd16',
NULL,
'usuario',
1
);

INSERT INTO categoria (nome, usuario_id) VALUES
('Arduino', 1),
('Mecânica', 2),
('Eletrônica', 3);

INSERT INTO projeto
(nome, descricao, visibilidade, usuario_id, categoria_id)
VALUES
(
'Robô Seguidor de Linha',
'Robô que segue uma linha usando sensores',
'publico',
1,
1
),
(
'Braço Robótico',
'Braço mecânico controlado por servo motores',
'equipe',
2,
2
),
(
'Drone Arduino',
'Drone controlado por Arduino com sensores',
'privado',
3,
3
);

INSERT INTO componente (nome, descricao, imagem) VALUES
(
'Arduino Uno',
'Microcontrolador',
'https://upload.wikimedia.org/wikipedia/commons/3/38/Arduino_Uno_-_R3.jpg'
),
(
'Sensor Ultrassônico',
'Mede distância',
'https://imgs.search.brave.com/Jeb6qq3pIOwx3frg9E5iH7_YLmR0r09GRcexR5aXDq0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu.bWVjYW5pY2FpbmR1c3RyaWFsLmNvbS5ici93cC1jb250ZW50L3VwbG9hZHMvMjAxMi8wNi9TZW5zb3ItdWx0cmFzcyVDMyVCNG5pY28uanBn'
),
(
'Servo Motor',
'Movimento angular',
'https://imgs.search.brave.com/TjXrujPor7WNt-O4Pcf25UYJ-iruGygJ3IJ5PCXB3rY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jdXJ0b2NpcmN1aXRvLmNvLmJyL21lZGlhL2NhdGFsb2cvcHJvZHVjdC9jYWNoZS8zMWE3YjlhOGQxYTM4MTgzYzk0ZmIyZGVjYTliYTE1Yy9fL3MvX3NfZV9zZXJ2b19tb3Rvcl8tX3NnOTBfLV90b3dlcnByb19fMV8xXzEuanBn'
);

INSERT INTO postagem_forum (conteudo, visibilidade, usuario_id, equipe_id) VALUES
('Como melhorar PID no robô?', 'publico', 1, NULL),
('Alguém tem código para servo?', 'equipe', 2, NULL);

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
(1, 1, 'coordenador'),
(1, 2, 'participante'),
(2, 3, 'coordenador'),
(3, 4, 'participante');

INSERT INTO equipe_usuario (equipe_id, usuario_id, categoria) VALUES
(1, 1, 'coordenador'),
(1, 2, 'participante'),
(2, 3, 'coordenador'),
(3, 4, 'participante');