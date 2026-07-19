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
  biografia VARCHAR(100),
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
  descricao VARCHAR(255) NOT NULL,
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
  descricao VARCHAR(255),
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

INSERT INTO equipe (nome, senha) VALUES
('Equipe Alpha', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2'),
('Equipe Beta', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2'),
('Equipe Gamma', '$2y$12$wRcrAEgHHM9t0SeZr4lmguQXNiVBbuuc6Pr.lGxCf/mVQSDdHYZD2');

INSERT INTO usuario (nome, nome_usuario, email, senha, imagem, regra, status) VALUES
('Walmonn Eduardo Barbosa Ramalho da Silva', 'WalmonnEduardo', 'walmonn.eduardo.tds2023@gmail.com', '$2y$12$/E551s2RVpWQgvvUnbq4E.ZZvKoaE2V1cNwPp2aTJokiCd6lle3/a', 'https://example.com/sai-dr-stone.webp', 'admin', 1),
('Guilherme Canever Wernke', 'guilherme', 'guilherme.wernke.tds2023@gmail.com', '$2y$12$pIqRCJLzv2Ia0jGlzk9VSOTAIK4YZk4/UB0Zs/3gjUerizr0DSTpW', 'https://example.com/thumb.jpg', 'admin', 1),
('Petrus Mito de Souza', 'petrus', 'petrus.souza.tds2023@gmail.com', '$2y$12$ECX23EPvPazaiSUN2asdwOLf0carz.YsWft/4Y93ziODDBmoq08PW', 'https://example.com/thumb2.png', 'admin', 1),
('teste', 'teste', 'teste@gmail.com', '$2y$12$twxVhhtHCFpYcOk8W02lq.PP/4hxFB9Urf9sV2lKPP4/JgF.Nkd16', NULL, 'usuario', 1);

INSERT INTO categoria (nome, usuario_id) VALUES
('Arduino', 1),
('Mecânica', 2),
('Eletrônica', 3);

INSERT INTO projeto (nome, descricao, visibilidade, usuario_id, categoria_id) VALUES
('Robô Seguidor de Linha', 'Robô que segue uma linha usando sensores', 'publico', 1, 1),
('Braço Robótico', 'Braço mecânico controlado por servo motores', 'publico', 2, 2),
('Drone Arduino', 'Drone controlado por Arduino com sensores', 'privado', 3, 3);

INSERT INTO componente (nome, descricao, imagem, usuario_id) VALUES
('Arduino Uno', 'Microcontrolador', 'https://upload.wikimedia.org/wikipedia/commons/3/38/Arduino_Uno_-_R3.jpg', 1),
('Sensor Ultrassônico', 'Mede distância', 'https://example.com/sensor.jpg', 1),
('Servo Motor', 'Movimento angular', 'https://example.com/servo.jpg', 1);

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

-- Componentes e códigos da versão em andamento do "Robô Seguidor de Linha" (projeto_id = 1)
INSERT INTO codigo (caminho, descricao) VALUES
('codigo_atual_v2.ino', 'Controle com PID ajustado');

INSERT INTO projeto_componente (quantidade, projeto_id, componente_id) VALUES
(1, 1, 1), -- 1 Arduino Uno
(2, 1, 2); -- 2 Sensores Ultrassônicos (Mudou na versão atual)

-- --------------------------------------------------
-- EXEMPLO DE POPULAÇÃO DO VERSIONAMENTO (BACKUP DA V1.0)
-- --------------------------------------------------

-- 1. Criamos o registro da versão antiga v1.0.0 do projeto 1 (versão = 1)
INSERT INTO projeto_versao (projeto_id, usuario_id, versao, descricao_alteracao) VALUES
(1, 1, 1, 'Primeira versão funcional usando apenas 1 sensor ultrassônico e lógica simples');

-- 2. Salvamos o código que pertencia à v1.0.0
INSERT INTO codigo (caminho, descricao) VALUES
('codigo_antigo_v1.ino', 'Código inicial sem PID');

-- 3. Vinculamos o código (id = 2) com a versão do projeto (projeto_versao_id = 1)
INSERT INTO codigo_versao (codigo_id, projeto_versao_id) VALUES
(2, 1);

-- 4. Salvamos os componentes que eram usados na v1.0.0 (projeto_versao_id = 1)
INSERT INTO projeto_versao_componente (quantidade, componente_id, projeto_versao_id) VALUES
(1, 1, 1), -- Usava 1 Arduino Uno
(1, 2, 1); -- Usava apenas 1 Sensor Ultrassônico (no atual usa 2)

-- Vinculando usuários finais
INSERT INTO projeto_usuario (projeto_id, usuario_id, papel) VALUES
(1, 1, 'coordenador'),
(1, 2, 'participante');

INSERT INTO equipe_usuario (equipe_id, usuario_id, papel) VALUES
(1, 1, 'coordenador'),
(1, 2, 'participante');