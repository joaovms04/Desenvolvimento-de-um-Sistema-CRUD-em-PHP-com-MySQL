-- ============================================================
--  Biblioteca Viva - script de criação do banco de dados
--  Como usar: phpMyAdmin > aba "SQL" (ou "Importar") > executar
--  Atenção: as tabelas são recriadas (DROP TABLE) a cada execução.
-- ============================================================

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `biblioteca_db`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `biblioteca_db`;

-- Remove na ordem certa (livros depende de generos)
DROP TABLE IF EXISTS `livros`;
DROP TABLE IF EXISTS `generos`;

-- ------------------------------------------------------------
--  Tabela: generos  (lado "1" do relacionamento)
-- ------------------------------------------------------------
CREATE TABLE `generos` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome`      VARCHAR(60)  NOT NULL,
    `descricao` VARCHAR(255) NULL,
    `cor`       CHAR(7)      NOT NULL DEFAULT '#1d5c63',   -- cor usada nas etiquetas (hexadecimal)
    `criado_em` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_generos_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Tabela: livros  (lado "N" do relacionamento)
--  Um gênero possui vários livros; cada livro pertence a um gênero.
--  ON DELETE RESTRICT: não deixa apagar um gênero que ainda tem livros.
-- ------------------------------------------------------------
CREATE TABLE `livros` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titulo`         VARCHAR(150) NOT NULL,
    `autor`          VARCHAR(100) NOT NULL,
    `ano_publicacao` SMALLINT UNSIGNED NULL,
    `isbn`           VARCHAR(13)  NULL,                    -- só dígitos (10 ou 13)
    `sinopse`        TEXT         NULL,
    `exemplares`     INT UNSIGNED NOT NULL DEFAULT 1,
    `status`         ENUM('disponivel', 'emprestado', 'reservado') NOT NULL DEFAULT 'disponivel',
    `genero_id`      INT UNSIGNED NOT NULL,
    `criado_em`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_livros_isbn` (`isbn`),
    KEY `idx_livros_titulo` (`titulo`),
    KEY `idx_livros_genero` (`genero_id`),
    CONSTRAINT `fk_livros_genero`
        FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Dados de exemplo
-- ------------------------------------------------------------
INSERT INTO `generos` (`nome`, `descricao`, `cor`) VALUES
('Romance',            'Histórias centradas em relações e conflitos humanos.',      '#c2410c'),
('Ficção Científica',  'Futuros possíveis, tecnologia e sociedade.',                '#1d4ed8'),
('Fantasia',           'Mundos imaginários, magia e grandes jornadas.',             '#7c3aed'),
('Tecnologia',         'Programação, engenharia de software e computação.',         '#0f766e'),
('História',           'Obras sobre acontecimentos e sociedades do passado.',       '#a16207'),
('Infantojuvenil',     'Leituras para crianças e jovens leitores.',                 '#be185d');

INSERT INTO `livros` (`titulo`, `autor`, `ano_publicacao`, `sinopse`, `exemplares`, `status`, `genero_id`) VALUES
('Dom Casmurro',                 'Machado de Assis',            1899, 'Bentinho revive a juventude e a dúvida sobre a fidelidade de Capitu.',            3, 'disponivel', 1),
('O Cortiço',                    'Aluísio Azevedo',             1890, 'A vida de moradores de um cortiço no Rio de Janeiro do século XIX.',               2, 'emprestado', 1),
('1984',                         'George Orwell',               1949, 'Em um Estado de vigilância total, Winston tenta guardar um pensamento livre.',     4, 'disponivel', 2),
('Duna',                         'Frank Herbert',               1965, 'Disputa pelo planeta deserto que produz a especiaria mais valiosa do universo.',   2, 'reservado',  2),
('O Hobbit',                     'J. R. R. Tolkien',            1937, 'Bilbo Bolseiro é arrastado para uma aventura em busca de um tesouro guardado por um dragão.', 3, 'disponivel', 3),
('Harry Potter e a Pedra Filosofal', 'J. K. Rowling',           1997, 'Um garoto descobre que é bruxo e começa os estudos em Hogwarts.',                  5, 'emprestado', 3),
('Código Limpo',                 'Robert C. Martin',            2008, 'Princípios e práticas para escrever código legível e fácil de manter.',            2, 'disponivel', 4),
('O Programador Pragmático',     'Andrew Hunt e David Thomas',  1999, 'Dicas práticas para quem quer evoluir no ofício de desenvolver software.',         1, 'disponivel', 4),
('Sapiens',                      'Yuval Noah Harari',           2011, 'Uma breve história da humanidade, da Revolução Cognitiva aos dias de hoje.',       3, 'disponivel', 5),
('O Pequeno Príncipe',           'Antoine de Saint-Exupéry',    1943, 'Um piloto perdido no deserto encontra um príncipe vindo de outro planeta.',        4, 'disponivel', 6);
