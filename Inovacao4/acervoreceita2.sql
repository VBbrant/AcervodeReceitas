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
  `imagem_perfil` varchar(120) default null,
  PRIMARY KEY (`idLogin`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `categoria` (
  `idCategoria` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `restaurante` (
  `idRestaurante` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(25) DEFAULT NULL,
  `endereco` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idRestaurante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `funcionario` (
  `idFun` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `rg` varchar(15) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `data_admissao` date DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `nome_fantasia` varchar(100) DEFAULT NULL,
  `telefone` varchar(25) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `idLogin` int DEFAULT NULL,
  `idCargo` int DEFAULT NULL,
  `idRestaurante` int DEFAULT NULL,  -- Chave estrangeira para o restaurante
  PRIMARY KEY (`idFun`),
  KEY `idLogin` (`idLogin`),
  KEY `idCargo` (`idCargo`),
  KEY `idRestaurante` (`idRestaurante`),  -- Índice para a chave estrangeira
  CONSTRAINT funcionario_ibfk_1 FOREIGN KEY (idLogin) REFERENCES usuario (idLogin) ON DELETE CASCADE,
  CONSTRAINT `funcionario_ibfk_2` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`),
  CONSTRAINT `funcionario_ibfk_3` FOREIGN KEY (`idRestaurante`) REFERENCES `restaurante` (`idRestaurante`)  -- Chave estrangeira para o restaurante
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `registro_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(32) NOT NULL,
  `nome` varchar(100),
  `rg` varchar(15),
  `data_nascimento` date NULL,
  `data_admissao` date NULL,
  `salario` decimal(10,2) NULL,
  `nome_fantasia` varchar(100) NULL,
  `telefone` varchar(25) NULL,
  `email` varchar(150) null,
  `idCargo` int NULL,
  `idRestaurante` int NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


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
  `link_imagem` varchar(255) DEFAULT NULL,
  `arquivo_imagem` varchar(100) DEFAULT NULL,
  `dataEntrega` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idLivro`),
  KEY `idEditor` (`idEditor`),
CONSTRAINT livro_ibfk_1 FOREIGN KEY (idEditor) REFERENCES funcionario (idFun) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `medida` (
  `idMedida` int NOT NULL AUTO_INCREMENT,
  `sistema` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idMedida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `receita` (
  `idReceita` int NOT NULL AUTO_INCREMENT,
  `nome_rec` varchar(100) NOT NULL,
  `data_criacao` date NOT NULL DEFAULT (CURRENT_DATE), -- Preenchido automaticamente com a data atual
  `modo_preparo` text,
  `num_porcao` int DEFAULT NULL,
  `descricao` text,
  `inedita` enum('S','N') NOT NULL,
  `link_imagem` varchar(255) DEFAULT NULL,
  `arquivo_imagem` varchar (255) DEFAULT NULL,
  `idCozinheiro` int DEFAULT NULL,
  `idCategoria` INT,
  `dataEntrega` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idReceita`),
  KEY `idCozinheiro` (`idCozinheiro`),
  CONSTRAINT `receita_ibfk_1` FOREIGN KEY (`idCozinheiro`) REFERENCES `funcionario` (`idFun`),
  CONSTRAINT `fk_receita_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Tabela intermediária para ligar receita, ingrediente e medida
CREATE TABLE `receita_ingrediente` (
  `idReceitaIngrediente` int NOT NULL AUTO_INCREMENT,
  `idReceita` int NOT NULL,
  `idIngrediente` int NOT NULL,
  `idMedida` int NOT NULL,
  `quantidade` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idReceitaIngrediente`),
  KEY `idReceita` (`idReceita`),
  KEY `idIngrediente` (`idIngrediente`),
  KEY `idMedida` (`idMedida`),
  CONSTRAINT `receita_ingrediente_ibfk_1` FOREIGN KEY (`idReceita`) REFERENCES `receita` (`idReceita`) ON DELETE CASCADE,
  CONSTRAINT `receita_ingrediente_ibfk_2` FOREIGN KEY (`idIngrediente`) REFERENCES `ingrediente` (`idIngrediente`) ON DELETE CASCADE,
  CONSTRAINT `receita_ingrediente_ibfk_3` FOREIGN KEY (`idMedida`) REFERENCES `medida` (`idMedida`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `livro_receita` (
  `idLivro` int NOT NULL,
  `idReceita` int NOT NULL,
  PRIMARY KEY (`idLivro`, `idReceita`),
  FOREIGN KEY (`idLivro`) REFERENCES `livro` (`idLivro`) ON DELETE CASCADE,
  FOREIGN KEY (`idReceita`) REFERENCES `receita` (`idReceita`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `degustacao` (
  `idDegustacao` int NOT NULL AUTO_INCREMENT,
  `data_degustacao` date DEFAULT NULL,
  `nota_degustacao` decimal(3,1) DEFAULT NULL,
  `idDegustador` int DEFAULT NULL,
  `idReceita` int DEFAULT NULL,
  PRIMARY KEY (`idDegustacao`),
  KEY `idDegustador` (`idDegustador`),
  KEY `idReceita` (`idReceita`),
  CONSTRAINT `degustacao_ibfk_1` FOREIGN KEY (`idDegustador`) REFERENCES `funcionario` (`idFun`) ON DELETE CASCADE,
  CONSTRAINT `degustacao_ibfk_2` FOREIGN KEY (`idReceita`) REFERENCES `receita` (`idReceita`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Se você já tem a chave estrangeira, pode alterá-la
ALTER TABLE degustacao
DROP FOREIGN KEY degustacao_ibfk_2;

-- Criar a chave estrangeira novamente com a opção ON DELETE CASCADE
ALTER TABLE degustacao
ADD CONSTRAINT degustacao_ibfk_2
FOREIGN KEY (idReceita) REFERENCES receita(idReceita)
ON DELETE CASCADE;


CREATE TABLE `referencia` (
  `idReferencia` int NOT NULL AUTO_INCREMENT,
  `idCozinheiro` int DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  PRIMARY KEY (`idReferencia`),
  KEY `idCozinheiro` (`idCozinheiro`),
  CONSTRAINT `referencia_ibfk_1` FOREIGN KEY (`idCozinheiro`) REFERENCES `funcionario` (`idFun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `comentario` (
  `idComentario` int NOT NULL AUTO_INCREMENT,
  `idDegustacao` int NOT NULL,
  `comentario_texto` text NOT NULL,
  `data_comentario` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idComentario`),
  KEY `idDegustacao` (`idDegustacao`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`idDegustacao`) REFERENCES `degustacao` (`idDegustacao`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `Metas` (
    `idMeta` INT PRIMARY KEY AUTO_INCREMENT,
    `idCozinheiro` INT NOT NULL,
    `metaReceitas` INT NOT NULL,
    `receitasAtingidas` INT DEFAULT 0, -- Armazena o número de receitas atingidas
    `dataInicio` DATE NOT NULL,
    `dataFinal` DATE NOT NULL,
    FOREIGN KEY (`idCozinheiro`) REFERENCES `funcionario`(`idFun`)
);

-- ---------------------------

CREATE TABLE log_sistema (
    idLog INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL, -- Referência ao id do funcionário (ou outro tipo de usuário)
    acao TEXT NOT NULL, -- Descrição da ação (exemplo: "Adicionou uma nova receita")
    tipo_acao ENUM('inclusao', 'edicao', 'exclusao', 'exclusaoEmMassa','outro') NOT NULL, -- Tipo de ação
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data e hora da ação
    FOREIGN KEY (idUsuario) REFERENCES funcionario(idFun) -- Referência à tabela de funcionários (ou outra tabela de usuários)
);

CREATE TABLE `senha_recuperacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expira_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;







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
INSERT INTO categoria (nome) VALUES
('Carnes'),
('Massas'),
('Sobremesas'),
('Bebidas');

-- Agora podemos inserir as receitas (depende de funcionario como cozinheiro)
INSERT INTO receita (nome_rec, modo_preparo, num_porcao, descricao, inedita, arquivo_imagem, idCozinheiro, idCategoria) VALUES
('Arras Carne Seca', 'Modo de preparo da carne seca...', 4, 'Deliciosa carne seca desfiada, temperada com especiarias.', 'S', 'receitas/imagens/arrasCarneSeca.png', 7, 1),
('Churrasco Maracanã', 'Modo de preparo do churrasco...', 6, 'O famoso Churrasco Maracanã é uma verdadeira explosão de sabores.', 'N', 'receitas/imagens/churrascoMaracana.png', 7, 1),
('Feijoada', 'Modo de preparo da feijoada...', 8, 'Feijoada completa e saborosa, feita com uma seleção especial de carnes.', 'N', 'receitas/imagens/feijoada.png', 7, 1),
('Strogonoff', 'Modo de preparo do strogonoff...', 5, 'Clássico strogonoff de carne, cremoso e rico em sabor.', 'N', 'receitas/imagens/strogonoff.png', 7, 1);

-- Atualizando as receitas com o modo de preparo completo sem números
UPDATE receita SET modo_preparo = 'Corte a carne seca em pedaços e deixe de molho em água por cerca de 12 horas, trocando a água algumas vezes para dessalgar.\nCozinhe a carne seca em uma panela de pressão por 30 minutos ou até ficar macia.\nApós o cozimento, desfie a carne e reserve.\nEm uma panela grande, aqueça o óleo e refogue a cebola e o alho até dourarem.\nAdicione a carne seca desfiada, o tomate picado e os temperos a gosto.\nCozinhe por alguns minutos, mexendo bem, até que todos os ingredientes estejam incorporados.\nSirva com arroz branco e uma salada verde.' WHERE nome_rec = 'Arras Carne Seca';

UPDATE receita SET modo_preparo = 'Tempere as carnes com sal grosso e deixe descansar por 15 minutos.\nAcenda o fogo e deixe a grelha esquentar bem antes de colocar as carnes.\nColoque as carnes na grelha e asse de acordo com o ponto desejado, virando de tempos em tempos.\nSirva o churrasco com farofa, vinagrete e pão de alho.' WHERE nome_rec = 'Churrasco Maracanã';

UPDATE receita SET modo_preparo = 'Deixe as carnes salgadas de molho na água de um dia para o outro, trocando a água algumas vezes para retirar o excesso de sal.\nCozinhe as carnes separadamente e reserve.\nEm uma panela grande, refogue o alho e a cebola no óleo até dourar.\nAcrescente o feijão e cubra com água.\nAdicione as carnes ao feijão e deixe cozinhar até o caldo engrossar e o feijão ficar macio.\nFinalize com o tempero verde a gosto e sirva com arroz branco e couve refogada.' WHERE nome_rec = 'Feijoada';

UPDATE receita SET modo_preparo = 'Corte a carne em tiras finas e tempere com sal e pimenta a gosto.\nEm uma panela, aqueça o óleo e doure a carne aos poucos.\nAdicione a cebola e o alho, refogando até ficarem transparentes.\nAcrescente o ketchup, a mostarda e o molho inglês.\nAdicione o creme de leite e misture bem.\nDeixe cozinhar em fogo baixo até o molho engrossar e a carne ficar macia.\nSirva o strogonoff com arroz branco e batata palha.' WHERE nome_rec = 'Strogonoff';



INSERT INTO ingrediente (nome, descricao) VALUES
('Carne Seca', 'Carne de boi desidratada e salgada'),
('Alho', 'Alho picado para tempero'),
('Óleo', 'Óleo vegetal para refogar'),
('Carne Bovina', 'Cortes variados de carne bovina'),
('Linguiça', 'Linguiça fresca para churrasco'),
('Feijão Preto', 'Feijão preto para feijoada'),
('Cebola', 'Cebola picada para tempero'),
('Creme de Leite', 'Creme de leite para cremosidade');

-- Inserção das unidades de medida únicas na tabela 'medida'
INSERT INTO medida (sistema) VALUES
('g'),          -- gramas
('dente'),      -- unidade de alho
('colheres'),   -- colheres
('kg'),         -- quilogramas
('unidade'),    -- unidade
('ml');         -- mililitros

-- Ingredientes para Arras Carne Seca
INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida, quantidade) VALUES
(5, 1, 1, 500),   -- 500g de Carne Seca
(5, 2, 2, 1),     -- 1 dente de Alho
(5, 3, 3, 2);     -- 2 colheres de Óleo

-- Ingredientes para Churrasco Maracanã
INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida, quantidade) VALUES
(6, 4, 4, 1),     -- 1 kg de Carne Bovina
(6, 5, 1, 300);   -- 300g de Linguiça

-- Ingredientes para Feijoada
INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida, quantidade) VALUES
(7, 6, 1, 250),   -- 250g de Feijão Preto
(7, 4, 4, 1),     -- 1 kg de Carne Bovina
(7, 7, 5, 1);     -- 1 unidade de Cebola

-- Ingredientes para Strogonoff
INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida, quantidade) VALUES
(8, 4, 4, 1),     -- 1 kg de Carne Bovina
(8, 8, 6, 200),   -- 200 ml de Creme de Leite
(8, 2, 2, 1);     -- 1 dente de Alho


-- Inserindo registros de degustação para o degustador com id 8
INSERT INTO degustacao (data_degustacao, nota_degustacao, idDegustador, idReceita) VALUES
('2023-05-01', 8.5, 8, 5),  -- Degustação da receita 'Arras Carne Seca'
('2023-05-02', 9.0, 8, 6),  -- Degustação da receita 'Churrasco Maracanã'
('2023-05-03', 8.8, 8, 7),  -- Degustação da receita 'Feijoada'
('2023-05-04', 9.2, 8, 8);  -- Degustação da receita 'Strogonoff'

-- Inserindo comentários para cada degustação
INSERT INTO comentario (idDegustacao, comentario_texto) VALUES
(1, 'A carne seca estava bem temperada e no ponto certo, excelente!'),
(2, 'O churrasco estava saboroso, especialmente a linguiça.'),
(3, 'A feijoada foi rica em sabores e bem servida.'),
(4, 'O strogonoff estava cremoso e equilibrado, muito bom.');



SET SQL_SAFE_UPDATES = 1;




