CREATE DATABASE IF NOT EXISTS robo_drive;
USE robo_drive;

-- TABELA: usuario
CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nome_usuario VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(1000) NOT NULL,
    biografia VARCHAR(2000),
    imagem VARCHAR(1000),
    regra ENUM('admin', 'usuario') NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- TABELA: equipe
CREATE TABLE IF NOT EXISTS equipe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    hash_acesso VARCHAR(64) UNIQUE,
    descricao VARCHAR(2000) NOT NULL,
    senha VARCHAR(1000) NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE
);

-- TABELA: categoria
CREATE TABLE IF NOT EXISTS categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario_id INT NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- TABELA: projeto
CREATE TABLE IF NOT EXISTS projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    hash_acesso VARCHAR(64) UNIQUE,
    senha VARCHAR(1000) NOT NULL,
    descricao VARCHAR(2000) NOT NULL,
    visibilidade ENUM('privado', 'equipe', 'publico') DEFAULT 'privado',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    categoria_id INT NOT NULL,
    equipe_id INT,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id),
    FOREIGN KEY (categoria_id) REFERENCES categoria(id),
    FOREIGN KEY (equipe_id) REFERENCES equipe(id)

);

-- TABELA: postagem_forum
CREATE TABLE IF NOT EXISTS postagem_forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo VARCHAR(2000) NOT NULL,
    visibilidade ENUM('equipe', 'publico') DEFAULT 'publico',
    usuario_id INT NOT NULL,
    equipe_id INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id),
    FOREIGN KEY (equipe_id) REFERENCES equipe(id)

);

-- TABELA: comentario_postagem_forum
CREATE TABLE IF NOT EXISTS comentario_postagem_forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo VARCHAR(2000) NOT NULL,
    postagem_forum_id INT NOT NULL,
    usuario_id INT NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (postagem_forum_id) REFERENCES postagem_forum(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)

);

-- TABELA: comentario_projeto
CREATE TABLE IF NOT EXISTS comentario_projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo VARCHAR(2000) NOT NULL,
    projeto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (projeto_id) REFERENCES projeto(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- TABELA: componente
CREATE TABLE IF NOT EXISTS componente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(2000) NOT NULL,
    imagem TEXT,
    usuario_id INT NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- TABELA: imagem_projeto
CREATE TABLE IF NOT EXISTS imagem_projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caminho VARCHAR(255) NOT NULL,
    projeto_id INT NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

-- TABELA: codigo
CREATE TABLE IF NOT EXISTS codigo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caminho VARCHAR(255) NOT NULL,
    descricao VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status BOOLEAN NOT NULL DEFAULT TRUE
);

-- TABELA: projeto_versao
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

-- TABELA: codigo_versao
CREATE TABLE IF NOT EXISTS codigo_versao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_id INT NOT NULL,
    projeto_versao_id INT NOT NULL,
    FOREIGN KEY (projeto_versao_id) REFERENCES projeto_versao(id),
    FOREIGN KEY (codigo_id) REFERENCES codigo(id)
);

-- TABELA: projeto_versao_componente
CREATE TABLE IF NOT EXISTS projeto_versao_componente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quantidade INT NOT NULL,
    componente_id INT NOT NULL,
    projeto_versao_id INT NOT NULL,
    FOREIGN KEY (componente_id) REFERENCES componente(id),
    FOREIGN KEY (projeto_versao_id) REFERENCES projeto_versao(id)
);

-- TABELA: projeto_usuario
CREATE TABLE IF NOT EXISTS projeto_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    papel ENUM('coordenador', 'participante') NOT NULL,
    status ENUM('pendente', 'ativo') NOT NULL DEFAULT 'pendente',
    FOREIGN KEY (projeto_id) REFERENCES projeto(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- TABELA: equipe_usuario
CREATE TABLE IF NOT EXISTS equipe_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipe_id INT NOT NULL,
    usuario_id INT NOT NULL,
    papel ENUM('coordenador', 'participante') NOT NULL,
    status ENUM('pendente', 'ativo') NOT NULL DEFAULT 'pendente',
    FOREIGN KEY (equipe_id) REFERENCES equipe(id), 
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- TABELA: denuncia
CREATE TABLE IF NOT EXISTS denuncia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('usuario', 'projeto', 'forum', 'comentario', 'componente', 'equipe') NOT NULL,
    alvo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    motivo VARCHAR(100) NOT NULL,
    descricao VARCHAR(2000),
    status BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- TABELA: recuperacao_senha
CREATE TABLE IF NOT EXISTS recuperacao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expira_em DATETIME NOT NULL,
    utilizado BOOLEAN DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE

);

-- TABELA: extra
CREATE TABLE IF NOT EXISTS extra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caminho VARCHAR(255) NOT NULL,
    descricao VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status BOOLEAN NOT NULL DEFAULT TRUE
);

-- TABELA: extra_versao
CREATE TABLE IF NOT EXISTS extra_versao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    extra_id INT NOT NULL,
    projeto_versao_id INT NOT NULL,
    FOREIGN KEY (projeto_versao_id) REFERENCES projeto_versao(id),
    FOREIGN KEY (extra_id) REFERENCES extra(id)
);

-- INSERTS DOS 3 PRIMEIROS USUARIOS
INSERT INTO `usuario` (`id`, `nome`, `nome_usuario`, `email`, `senha`, `biografia`, `imagem`, `regra`, `status`, `criado_em`) VALUES
    (1,'Walmonn Eduardo','WalmonnEduardo','walmonn.eduardo.tds2023@gmail.com','$2y$12$ih4V7SmaYKHc/UP0qvoV6.p2F7fas3o7ULlsEqQ6qctKfq36MUyQW','Desenvolvedor e entusiasta de rob&oacute;tica.','store/users/user-1/img/1a36a43e55d06281855fb503334e24a9.jpg','admin',1,'2026-10-07 15:27:40'),
    (2,'Guilherme Canever Wernke','guilherme','guilherme.wernke.tds2023@gmail.com','$2y$12$pIqRCJLzv2Ia0jGlzk9VSOTAIK4YZk4/UB0Zs/3gjUerizr0DSTpW','Professor de rob&oacute;tica.','store/users/user-2/img/4b4c5d887ba820581966f5215c2d7b8e.jpg','admin',1,'2026-10-07 15:27:40'),
    (3,'Petrus Mito de Souza','petrus','petrus.souza.tds2023@gmail.com','$2y$12$ECX23EPvPazaiSUN2asdwOLf0carz.YsWft/4Y93ziODDBmoq08PW','Especialista em eletr&ocirc;nica.','store/users/user-3/img/f79dc89b68ade760f62dd93b3d8479a1.jpg','admin',1,'2026-10-07 15:27:40');
    (4,'RoboDrive Admin','robodriveadmin','robodriveifpr@gmail.com','$2y$12$LRIGiSrp3PoAKh/aXD.jA.YzNcmAxuT8OdpKIaZp2.57arOk.EfNK','admin.','store/users/user-3/img/f79dc89b68ade760f62dd93b3d8479a1.jpg','admin',1,'2026-10-07 15:27:40');

-- INSERTS DAS CATEGORIAS DOS USUARIOS
INSERT INTO `categoria` (`id`, `nome`, `usuario_id`, `status`) VALUES
    (1,'Meus projetos',1,1),
    (2,'Meus projetos',2,1),
    (3,'Meus projetos',3,1);
    (4,'Meus projetos',4,1);

-- INSERTS DOS COMPONENTES
INSERT INTO `componente` (`id`, `nome`, `descricao`, `imagem`, `usuario_id`, `status`) VALUES
    (1,'Term&iacute;stor','Term&iacute;stor &eacute; um dispositivo semicondutor sens&iacute;vel &agrave; temperatura.','store/components/54e60fcb297cf41d6949e285f0602014.jpg',1,1),
    (2,'Arduino UNO','&Eacute; a placa mais famosa e cl&aacute;ssica do mundo maker, ideal para iniciantes e prototipagem r&aacute;pida. Baseada no microcontrolador ATmega328P, ela &eacute; robusta, f&aacute;cil de usar e possui uma vasta comunidade com milhares de tutoriais e bibliotecas prontas.\r\n\r\nDetalhes das Portas:\r\n\r\nPinos Digitais (0 a 13): 14 pinos que podem ser usados como entrada (INPUT) ou sa&iacute;da (OUTPUT). Os pinos 3, 5, 6, 9, 10 e 11 possuem a marca&ccedil;&atilde;o PWM (onda quadrada simulada), permitindo controlar velocidade de motores e intensidade de LEDs.\r\n\r\nPinos Anal&oacute;gicos (A0 a A5): 6 pinos dedicados &agrave; leitura de sensores anal&oacute;gicos (como potenci&ocirc;metros ou sensores de luz), convertendo sinais de 0V a 5V em valores num&eacute;ricos de 0 a 1023.\r\n\r\nAlimenta&ccedil;&atilde;o: Pinos de 5V, 3.3V e GND, al&eacute;m do pino Vin para alimentar a placa com fontes externas (7V a 12V','store/components/ccbf87429e79178cf1055da1db7d1e73.jpg',1,1),
    (3,'Arduino NANO','O Arduino Nano &eacute; essencialmente uma vers&atilde;o em miniatura do Arduino UNO, mantendo o mesmo microcontrolador (ATmega328P) e processamento. Sua grande vantagem &eacute; o formato compacto projetado especificamente para ser encaixado diretamente em placas de ensaio (protoboards), economizando espa&ccedil;o em projetos compactos.\r\n\r\nDetalhes das Portas:\r\n\r\nPinos Digitais (D0 a D13): 14 pinos digitais, sendo que os pinos 3, 5, 6, 9, 10 e 11 tamb&eacute;m oferecem suporte a PWM.\r\n\r\nPinos Anal&oacute;gicos (A0 a A7): Diferente do UNO que tem apenas 6, o Nano possui 8 pinos anal&oacute;gicos (incluindo A6 e A7), o que d&aacute; uma vantagem extra para conectar mais sensores.\r\n\r\nAlimenta&ccedil;&atilde;o: Opera a 5V, dispondo de pinos de energia equivalentes aos do irm&atilde;o maior.','store/components/ddef9c4c9ef40aabae63e5677ac342e6.webp',1,1),
    (4,'ESP32','Um verdadeiro monstro para projetos de Internet das Coisas (IoT) e rob&oacute;tica avan&ccedil;ada. O ESP32 possui um processador poderoso de 32 bits (muitas vezes dual-core) e j&aacute; vem com Wi-Fi e Bluetooth (Dual-mode: Cl&aacute;ssico e BLE) integrados de f&aacute;brica, permitindo que seu rob&ocirc; ou projeto se conecte facilmente &agrave; internet e a smartphones.\r\n\r\nDetalhes das Portas:\r\n\r\nGPIOs Multifuncionais: Possui mais de 30 pinos GPIO program&aacute;veis que podem assumir diversas fun&ccedil;&otilde;es internas (como PWM, conversor anal&oacute;gico-digital ADC, e conversor digital-anal&oacute;gico DAC).\r\n\r\nComunica&ccedil;&atilde;o Avan&ccedil;ada: Suporta nativamente m&uacute;ltiplos barramentos UART (para comunica&ccedil;&atilde;o serial), I2C (para telas e sensores) e SPI (para cart&otilde;es SD e displays r&aacute;pidos).\r\n\r\nAten&ccedil;&atilde;o &agrave; Tens&atilde;o: Diferente dos Arduinos, o ESP32 opera a 3.3V e seus pinos n&atilde;o toleram 5V diretos, sob o risco de queimar o chip.','store/components/6c11c2754f1b3e405a94abe3313fd8de.webp',1,1),
    (5,'ESP8266','O chip que revolucionou a automa&ccedil;&atilde;o residencial de baixo custo. O ESP8266 &eacute; um microcontrolador Wi-Fi extremamente barato e eficiente. Ele &eacute; muito utilizado para transformar projetos comuns em dispositivos conectados &agrave; nuvem (IoT), como interruptores inteligentes e sensores remotos.\r\n\r\nDetalhes das Portas:\r\n\r\nPinos GPIO (D0 a D8 / GPIOs): Possui cerca de 17 pinos GPIO, mas na pr&aacute;tica (especialmente nas placas de desenvolvimento como o NodeMCU), cerca de 11 pinos s&atilde;o facilmente utiliz&aacute;veis para fins gerais.\r\n\r\nEntrada Anal&oacute;gica (A0): Possui apenas um pino anal&oacute;gico de entrada dedicado para leitura de tens&otilde;es (geralmente limitado a 0V&ndash;1V ou 0V&ndash;3.3V dependendo do divisor de tens&atilde;o da placa).\r\n\r\nAlimenta&ccedil;&atilde;o e L&oacute;gica: Assim como o ESP32, ele opera estritamente em 3.3V, exigindo cuidado ao interagir com sensores de 5V.','store/components/977cb65ff938aa6ecc03212f91bcd2f4.webp',1,1),
    (6,'Ponte H Dupla L298N','&Eacute; o m&oacute;dulo de driver de motores mais popular da rob&oacute;tica educacional e maker. Como microcontroladores (Arduino/ESP32) fornecem apenas sinais de baixa corrente, eles queimariam se ligados diretamente a motores. A L298N funciona como uma chave de alta pot&ecirc;ncia que permite controlar a dire&ccedil;&atilde;o (frente/r&eacute;) e a velocidade de at&eacute; dois motores DC independentes (ou um motor de passo) utilizando fontes de energia externas de maior tens&atilde;o.\r\n\r\nDetalhes das Portas e Pinos:\r\n1. Alimenta&ccedil;&atilde;o de Pot&ecirc;ncia\r\n12V (ou VS): Entrada principal para alimentar os motores (suporta de 7V a 35V).\r\n\r\nGND (Terra): Terra comum. Importante: deve ser interligado obrigatoriamente ao GND do microcontrolador para fechar o circuito de refer&ecirc;ncia.\r\n\r\n5V: Sa&iacute;da do regulador interno (fornece 5V para alimentar o microcontrolador). Se usar baterias acima de 12V, remova o jumper ao lado deste pino para evitar danos.\r\n\r\n2. Entradas de Controle (L&oacute;gica)\r\nIN1 e IN2: Controlam a dire&ccedil;&atilde;o do Motor A. (Ex: IN1=High e IN2=Low gira para frente; invertendo, gira para tr&aacute;s).\r\n\r\nIN3 e IN4: Controlam a dire&ccedil;&atilde;o do Motor B da mesma forma.\r\n\r\nENA e ENB (Enable): Controlam a velocidade. Com o jumper padr&atilde;o, o motor roda no m&aacute;ximo. Removendo o jumper e ligando a uma porta PWM do microcontrolador, &eacute; poss&iacute;vel regular a velocidade via c&oacute;digo (0 a 255). ENA controla o Motor A; ENB controla o Motor B.\r\n\r\n3. Sa&iacute;das para os Motores\r\nOUT1 e OUT2: Bornes de conex&atilde;o para os dois fios do Motor A.\r\n\r\nOUT3 e OUT4: Bornes de conex&atilde;o para os dois fios do Motor B.','store/components/6e7da870abd244ae4e58b53313bda84b.webp',1,1),
    (7,'Regulador de tens&atilde;o LM2596','M&oacute;dulo regulador de tens&atilde;o chaveado do tipo step-down (rebaixador) de alta efici&ecirc;ncia. Diferente dos reguladores lineares tradicionais que desperdi&ccedil;am o excesso de tens&atilde;o em forma de calor, o LM2596 converte a tens&atilde;o de entrada de forma eficiente para uma sa&iacute;da menor e ajust&aacute;vel. &Eacute; amplamente utilizado na rob&oacute;tica para alimentar microcontroladores, motores ou sensores a partir de baterias de maior voltagem (como Li-ion ou LiPo), garantindo estabilidade e evitando perdas energ&eacute;ticas.\r\n\r\nDetalhes das Portas, Pinos e Componentes:\r\n\r\nEntradas e Sa&iacute;das (Terminais / Bornes):\r\n\r\nIN+ (Entrada Positiva): Conex&atilde;o para a fonte de energia de entrada (suporta tipicamente de 4.5V a 40V).\r\n\r\nIN- (Entrada Negativa / GND): Terra da fonte de alimenta&ccedil;&atilde;o de entrada.\r\n\r\nOUT+ (Sa&iacute;da Positiva): Terminal que fornece a tens&atilde;o regulada e reduzida para o circuito (ajust&aacute;vel de 1.23V a 37V).\r\n\r\nOUT- (Sa&iacute;da Negativa / GND): Terra comum de sa&iacute;da (geralmente interligado internamente ao IN-).\r\n\r\nComponentes de Ajuste e Funcionamento:\r\n\r\nPotenci&ocirc;metro (Trimpot): Pequeno parafuso azul de ajuste fino. Girando no sentido anti-hor&aacute;rio, a tens&atilde;o de sa&iacute;da (OUT+) aumenta; no sentido hor&aacute;rio, diminui. (Dica: ajuste sempre com um mult&iacute;metro antes de conectar ao circuito).\r\n\r\nIndutor e Capacitores: Componentes vis&iacute;veis na placa respons&aacute;veis por armazenar energia por comuta&ccedil;&atilde;o e filtrar ru&iacute;dos el&eacute;tricos.\r\n\r\nEspecifica&ccedil;&otilde;es R&aacute;pidas: Suporta correntes de at&eacute; 2A (ou at&eacute; 3A com dissipador adicional) e possui efici&ecirc;ncia de convers&atilde;o superior a 90%.','store/components/4dd0b6fec91e99a1211da71746825d93.webp',1,1),
    (8,'Mini Motor Redutor DC 6V N20 (1000 RPM)','O Mini Motor DC N20 com caixa de redu&ccedil;&atilde;o met&aacute;lica &eacute; um atuador rotativo extremamente compacto, leve e de alto desempenho, muito utilizado em rob&oacute;tica m&oacute;vel de pequeno porte (como rob&ocirc;s seguidores de linha, mini sumos e carrinhos aut&ocirc;nomos). Como motores DC comuns giram em velocidades alt&iacute;ssimas com pouqu&iacute;ssimo torque, a caixa de engrenagens de metal acoplada reduz a rota&ccedil;&atilde;o final (neste caso, para cerca de 1000 RPM) e multiplica drasticamente a for&ccedil;a mec&acirc;nica gerada, permitindo tracionar pequenos rob&ocirc;s com efici&ecirc;ncia.\r\n\r\nDetalhes das Conex&otilde;es e Caracter&iacute;sticas F&iacute;sicas:\r\n\r\nTerminais de Conex&atilde;o (Pinos):\r\n\r\nO motor possui dois terminais met&aacute;licos (sold&aacute;veis) localizados na parte traseira.\r\n\r\nPolaridade: N&atilde;o h&aacute; polaridade fixa obrigat&oacute;ria; inverter a conex&atilde;o dos fios positivo e negativo far&aacute; simplesmente com que o sentido de rota&ccedil;&atilde;o do eixo se inverta (hor&aacute;rio para anti-hor&aacute;rio). Para control&aacute;-lo de forma inteligente, ele deve ser ligado a um driver de motor (como a Ponte H L298N ou m&oacute;dulos menores como o TB6612FNG).\r\n\r\nEixo de Sa&iacute;da:\r\n\r\nPossui um eixo met&aacute;lico (geralmente em formato de &quot;D&quot; ou estriado), projetado para encaixe firme de rodas pequenas, engrenagens ou polias sem risco de patina&ccedil;&atilde;o.\r\n\r\nEspecifica&ccedil;&otilde;es T&eacute;cnicas R&aacute;pidas:\r\n\r\nTens&atilde;o de Opera&ccedil;&atilde;o: Tipicamente nominal de 6V (mas opera em uma faixa segura geralmente de 3V a 6V).\r\n\r\nRota&ccedil;&atilde;o: 1000 RPM (Rota&ccedil;&otilde;es Por Minuto) sem carga.\r\n\r\nConstru&ccedil;&atilde;o: Caixa de engrenagens totalmente met&aacute;lica, o que garante maior durabilidade e resist&ecirc;ncia ao desgaste em compara&ccedil;&atilde;o &agrave;s vers&otilde;es com engrenagens de pl&aacute;stico.','store/components/09227593b2ea27cd00bdcd9b8ce92191.webp',1,1),
    (9,'M&oacute;dulo Sensor Fototransistor Qre1113 Segue Faixa 8 Canais','Placa compacta com 8 pares de sensores &oacute;pticos (LED infravermelho e fototransistor) para leitura de contraste de superf&iacute;cies em rob&ocirc;s seguidores de linha de alta performance.\r\n\r\nDetalhes das Portas:\r\n\r\nAlimenta&ccedil;&atilde;o: VCC (3.3V a 5V) e GND.\r\n\r\nSa&iacute;das: Pinos OUT1 at&eacute; OUT8, que fornecem sinais anal&oacute;gicos ou digitais (dependendo da varia&ccedil;&atilde;o da placa) proporcionais &agrave; luz refletida pela pista.','store/components/1e6b750a98a026eaafa48eb50b259920.webp',1,1),
    (10,'Sensor de Luz / Segue Faixa Infravermelho TCRT5000','M&oacute;dulo &oacute;ptico tradicional na rob&oacute;tica educacional para rob&ocirc;s seguidores de linha e detec&ccedil;&atilde;o de obst&aacute;culos pr&oacute;ximos. Ele utiliza um LED emissor de luz infravermelha e um fototransistor receptor. Superf&iacute;cies claras refletem o feixe de luz, enquanto superf&iacute;cies escuras o absorvem. Um circuito comparador (chip LM393) a bordo processa o sinal, convertendo-o em uma resposta digital ou anal&oacute;gica clara para o microcontrolador.\r\n\r\nDetalhes das Portas e Pinos:\r\n\r\nPinos de Interface:\r\n\r\nVCC: Alimenta&ccedil;&atilde;o positiva da placa (opera tipicamente a 5V).\r\n\r\nGND: Terra comum de refer&ecirc;ncia.\r\n\r\nDO (Sa&iacute;da Digital): Envia n&iacute;vel l&oacute;gico (HIGH ou LOW) alterado conforme a detec&ccedil;&atilde;o da linha.\r\n\r\nAO (Sa&iacute;da Anal&oacute;gica - em alguns modelos): Fornece tens&atilde;o cont&iacute;nua proporcional &agrave; quantidade de luz refletida para leituras de gradua&ccedil;&atilde;o.\r\n\r\nComponentes de Ajuste e Indica&ccedil;&atilde;o:\r\n\r\nTrimpot (Potenci&ocirc;metro): Parafuso azul de ajuste para calibrar a sensibilidade da detec&ccedil;&atilde;o de contraste conforme a ilumina&ccedil;&atilde;o do ambiente.\r\n\r\nLEDs Indicadores: LED PWR (indica energia ligada) e LED DO (acende quando o sensor detecta a linha, facilitando testes).','store/components/f3677f20d80e44f5631500e44ce10474.webp',1,1),
    (11,'Teste','gostei','store/components/5a893e68a00c1b6e58c2038e54225202.jpeg',1,0);
