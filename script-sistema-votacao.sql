DROP DATABASE IF EXISTS `sistema_votacao`;
CREATE DATABASE IF NOT EXISTS sistema_votacao;
USE sistema_votacao;

-- Tabela de condomínios
CREATE TABLE condominios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    telefone VARCHAR(15),
    cep VARCHAR(9)
);

-- Tabela de usuários
CREATE TABLE usuarios (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- hash
    status ENUM('ativo', 'rejeitado', 'pendente') DEFAULT 'pendente',
    perfil TINYINT NOT NULL CHECK (perfil IN (1,2,3)), -- 1=Admin, 2=Síndico, 3=Morador
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    cpf VARCHAR(14) UNIQUE,
    id_condominio INT,
    estado VARCHAR(50) NULL,
    cidade VARCHAR(100) NULL,
    bloco VARCHAR(20) NULL,
    casa VARCHAR(20) NULL,
    FOREIGN KEY (id_condominio) REFERENCES condominios(id)
);

-- Tabela de pautas (votações)
CREATE TABLE pautas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME NOT NULL,
    status ENUM('ativa', 'encerrada') DEFAULT 'ativa',
    id_sindico INT,
    FOREIGN KEY (id_sindico) REFERENCES usuarios(codigo)
);

-- Tabela de opções de voto
CREATE TABLE opcoes_voto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pauta INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_pauta) REFERENCES pautas(id)
);

-- Tabela de votos
CREATE TABLE votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_opcao INT NOT NULL,
    id_pauta INT NOT NULL,
    data_voto DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(codigo),
    FOREIGN KEY (id_opcao) REFERENCES opcoes_voto(id),
    FOREIGN KEY (id_pauta) REFERENCES pautas(id),
    UNIQUE (id_usuario, id_pauta)
);

CREATE TABLE atividades (
id INT AUTO_INCREMENT PRIMARY KEY,
id_usuario INT,
descricao VARCHAR(255) NOT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_usuario) REFERENCES usuarios(codigo)
);

DELIMITER $$

CREATE TRIGGER tg_usuarios_insert
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
DECLARE tipo_perfil VARCHAR(20);
SET tipo_perfil = CASE NEW.perfil
WHEN 1 THEN 'Admin'
WHEN 2 THEN 'Síndico'
WHEN 3 THEN 'Morador'
ELSE 'Usuário'
END;
INSERT INTO atividades (descricao)
VALUES (CONCAT('Novo ', tipo_perfil, ' "', NEW.nome, '" criado.'));
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER tg_usuarios_update
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    DECLARE tipo_perfil VARCHAR(20);

    -- define o tipo de perfil
    SET tipo_perfil = CASE NEW.perfil
        WHEN 1 THEN 'Admin'
        WHEN 2 THEN 'Síndico'
        WHEN 3 THEN 'Morador'
        ELSE 'Usuário'
    END;

    -- insere na tabela de atividades somente se houver alteração de status
    IF NEW.status <> OLD.status THEN
        INSERT INTO atividades (descricao)
        VALUES (CONCAT(tipo_perfil, ' "', NEW.nome, '" teve seu status alterado para ', NEW.status, '.'));
    END IF;
END$$

DELIMITER ;


-- Inserir condomínios primeiro, pois usuários fazem referência a eles.
INSERT INTO condominios (nome, endereco, telefone, cep) VALUES
('Vila da Névoa', 'Jardim das Flores, 175', '(98) 2558-8033', '65058-351'),
('Condomínio Sol Nascente', 'Avenida Primária, 456', '(47) 2528-8381', '89280-331');

-- Em seguida, inserir usuários, pois pautas, opções de voto e votos farão referência a eles.
-- As senhas são armazenadas como MD5 para exemplo.
-- admin terá codigo=1
-- sindico1 terá codigo=2
-- morador1 terá codigo=3
INSERT INTO usuarios (usuario, senha, status, perfil, nome, email, telefone, cpf, id_condominio, estado, cidade, bloco, casa) VALUES
('admin', MD5('admin123'), 'ativo', 1, 'Luiza Raquel Santos', 'admin@votacomunidade.com', '(98)98795-4552', '323.689.962-06' , NULL, NULL, NULL, NULL, NULL),
('sindico1', MD5('sindico123'), 'ativo', 2, 'Caio Cauã Tiago Duarte', 'caio.caua.duarte@viladanevoa.com', '(51)99786-5956', '959.354.714-26', 2, 'SP', 'Ribeirão Preto', NULL, NULL),
('morador1', MD5('morador123'), 'ativo', 3, 'Cecília Sophia Drumond', 'ceciliasophiadrumond@gmail.com.br', '(55)98219-6082', '812.229.287-95', 1, 'SP', 'Mogi da Cruzes', 'Bloco 7', '708');

-- Inserir pautas após os usuários, pois pautas referenciam usuários (id_sindico).
-- A pauta inserida terá id=1. O sindico1 tem codigo=2.
INSERT INTO pautas (titulo, descricao, data_inicio, data_fim, status, id_sindico) VALUES
('Reforma da piscina', 'Decidir sobre a reforma da piscina do condomínio.', '2025-08-10 08:00:00', '2025-08-12 18:00:00', 'ativa', 2);

-- Inserir opções de voto após as pautas, pois as opções de voto referenciam pautas (id_pauta).
-- A pauta 'Reforma da piscina' tem id=1.
-- A primeira opção ('Sim...') terá id=1, a segunda ('Não...') terá id=2.
INSERT INTO opcoes_voto (id_pauta, descricao) VALUES
(1, 'Sim, concordo com a reforma'),
(1, 'Não, sou contra a reforma');

-- Inserir voto após usuários, pautas e opções de voto.
-- O morador1 tem codigo=3. A pauta tem id=1. A opção 'Sim...' tem id=1.
INSERT INTO votos (id_usuario, id_opcao, id_pauta) VALUES
(3, 1, 1);

USE sistema_votacao;

SELECT * FROM usuarios;

SELECT * FROM pautas;
