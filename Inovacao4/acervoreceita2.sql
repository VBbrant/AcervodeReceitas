drop database if exists acervoreceita2;
create database	 if not exists acervoreceita2;
use acervoreceita2;

CREATE TABLE `cargo` (
  `idCargo` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`idCargo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `usuario` (
  `idLogin` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`idLogin`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `categoria` (
  `idCategoria` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `funcionario` (
  `idFun` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `rg` varchar(15) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `data_admissao` date DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `nome_fantasia` varchar(100) DEFAULT NULL,
  `idLogin` int DEFAULT NULL,
  `idCargo` int DEFAULT NULL,
  PRIMARY KEY (`idFun`),
  KEY `idLogin` (`idLogin`),
  KEY `idCargo` (`idCargo`),
  CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`idLogin`) REFERENCES `usuario` (`idLogin`),
  CONSTRAINT `funcionario_ibfk_2` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `degustacao` (
  `idDegustacao` int NOT NULL AUTO_INCREMENT,
  `data_degustacao` date DEFAULT NULL,
  `nota_degustacao` decimal(3,1) DEFAULT NULL,
  `idDegustador` int DEFAULT NULL,
  PRIMARY KEY (`idDegustacao`),
  KEY `idDegustador` (`idDegustador`),
  CONSTRAINT `degustacao_ibfk_1` FOREIGN KEY (`idDegustador`) REFERENCES `funcionario` (`idFun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `ingrediente` (
  `idIngrediente` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  PRIMARY KEY (`idIngrediente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `livro` (
  `idLivro` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `idEditor` int DEFAULT NULL,
  PRIMARY KEY (`idLivro`),
  KEY `idEditor` (`idEditor`),
  CONSTRAINT `livro_ibfk_1` FOREIGN KEY (`idEditor`) REFERENCES `funcionario` (`idFun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `medida` (
  `idMedida` int NOT NULL AUTO_INCREMENT,
  `quantidade` decimal(10,2) NOT NULL,
  `sistema` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idMedida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `receita` (
  `idReceita` int NOT NULL AUTO_INCREMENT,
  `nome_rec` varchar(100) NOT NULL,
  `data_criacao` date DEFAULT NULL,
  `modo_preparo` text,
  `num_porcao` int DEFAULT NULL,
  `descricao` text,
  `inedita` enum('S','N') NOT NULL,
  `link_imagem` varchar(255) DEFAULT NULL,
  `idCozinheiro` int DEFAULT NULL,
  PRIMARY KEY (`idReceita`),
  KEY `idCozinheiro` (`idCozinheiro`),
  CONSTRAINT `receita_ibfk_1` FOREIGN KEY (`idCozinheiro`) REFERENCES `funcionario` (`idFun`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `referencia` (
  `idReferencia` int NOT NULL AUTO_INCREMENT,
  `idCozinheiro` int DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  PRIMARY KEY (`idReferencia`),
  KEY `idCozinheiro` (`idCozinheiro`),
  CONSTRAINT `referencia_ibfk_1` FOREIGN KEY (`idCozinheiro`) REFERENCES `funcionario` (`idFun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `restaurante` (
  `idRestaurante` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idRestaurante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


SET SQL_SAFE_UPDATES = 0;

-- Inserir cargos primeiro (não tem dependências)
INSERT INTO cargo (nome) VALUES
('ADM'),
('Cozinheiro'),
('Editor'),
('Degustador'),
('Analista de TI');

INSERT INTO usuario (nome, email, senha)VALUES
('Shin','shineinouzen51@gmail.com','$2y$10$ah/mXbDaJW53EsYwxud7ku3Q8/GjvBlPsGnu4Y0hQ9EEUdoUHPC92'),
('Lena','vladilenaMilize51@gmail.com','$2y$10$ah/mXbDaJW53EsYwxud7ku3Q8/GjvBlPsGnu4Y0hQ9EEUdoUHPC92'),
('Asuna','asunayuuki51@gmail.com','$2y$10$ah/mXbDaJW53EsYwxud7ku3Q8/GjvBlPsGnu4Y0hQ9EEUdoUHPC92'),
('Kirito','kiritokirigaya51@gmail.com','$2y$10$ah/mXbDaJW53EsYwxud7ku3Q8/GjvBlPsGnu4Y0hQ9EEUdoUHPC92');

-- Inserir funcionários (depende de cargo)
INSERT INTO funcionario (nome, rg, data_nascimento, data_admissao, salario, nome_fantasia, idLogin, idCargo) VALUES
('Shinei Nouzen', '123456789', '1990-05-12', '2020-02-01', 3500.00, NULL, 5, 6),
('Vladilena Milize', '987654321', '1985-11-25', '2019-07-10', 2800.00, NULL, 6, 7),
('Asuna Yuuki', '456789123', '1992-03-18', '2021-03-15', 4000.00, NULL, 7, 8),
('Kirigaya Kazuto', '789123456', '1988-08-05', '2020-10-12', 3200.00, NULL, 8, 9);

-- Inserir categorias (não tem dependências)
INSERT INTO categoria (descricao) VALUES
('Carnes'),
('Massas'),
('Sobremesas'),
('Bebidas');

-- Agora podemos inserir as receitas (depende de funcionario como cozinheiro)
INSERT INTO receita (nome_rec, data_criacao, modo_preparo, num_porcao, descricao, inedita, link_imagem, idCozinheiro) VALUES
('Arras Carne Seca', '2023-01-15', 'Modo de preparo da carne seca...', 4, 'Deliciosa carne seca desfiada, temperada com especiarias.', 'S', '../imagens/arrasCarneSeca.png', 7),
('Churrasco Maracanã', '2023-02-20', 'Modo de preparo do churrasco...', 6, 'O famoso Churrasco Maracanã é uma verdadeira explosão de sabores.', 'N', '../imagens/churrascoMaracana.png', 7),
('Feijoada', '2023-03-10', 'Modo de preparo da feijoada...', 8, 'Feijoada completa e saborosa, feita com uma seleção especial de carnes.', 'N', '../imagens/feijoada.png', 7),
('Strogonoff', '2023-04-25', 'Modo de preparo do strogonoff...', 5, 'Clássico strogonoff de carne, cremoso e rico em sabor.', 'N', '../imagens/strogonoff.png', 7);

-- Inserir degustações (depende de funcionário como degustador)
INSERT INTO degustacao (data_degustacao, nota_degustacao, idDegustador) VALUES
('2023-09-15', 9.5, 8),
('2023-10-01', 8.7, 8),
('2023-10-12', 9.0, 8),
('2023-10-18', 8.3, 8);

SET SQL_SAFE_UPDATES = 1;




