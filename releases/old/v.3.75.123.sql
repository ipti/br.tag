CREATE TABLE farmer_register (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    phone VARCHAR(11) NULL,
    group_type ENUM('Fornecedor Individual', 'Grupo Formal', 'Grupo Informal') NULL,
    PRIMARY KEY (id)
);

CREATE TABLE farmer_foods (
    id INT(11) NOT NULL AUTO_INCREMENT,
    food_fk INT(11),
    farmer_fk INT(11),
    measurementUnit ENUM('g', 'Kg', 'l', 'pacote', 'unidade') NULL,
    amount FLOAT NOT NULL,
    PRIMARY KEY (id)
);

ALTER TABLE farmer_foods
ADD CONSTRAINT fk_farmer_foods_food
FOREIGN KEY (food_fk) REFERENCES food(id);

ALTER TABLE farmer_foods
ADD CONSTRAINT fk_farmer_foods_farmer_register
FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id);
-- =====================================================================

CREATE TABLE food_notice (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    date DATE NOT NULL
);
CREATE TABLE food_notice_item (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(1000) NULL,
    measurement VARCHAR(20) NOT NULL,
    year_amount int(11) NOT NULL
);
CREATE TABLE food_notice_vs_food_notice_item (
    id INT PRIMARY KEY AUTO_INCREMENT,
    food_notice_id INT,
    food_notice_item_id INT,
    FOREIGN KEY (food_notice_id) REFERENCES food_notice(id),
    FOREIGN KEY (food_notice_item_id) REFERENCES food_notice_item(id)
);
ALTER TABLE food_notice_item
ADD COLUMN food_id INT,
ADD CONSTRAINT fk_food_notice_item_food
    FOREIGN KEY (food_id)
    REFERENCES food(id);
ALTER TABLE food_notice_item
MODIFY COLUMN year_amount VARCHAR(20);

-- ==================================================================================================

ALTER TABLE food_request ADD status ENUM('Em andamento','Finalizado') DEFAULT 'Em andamento'

ALTER TABLE food_request DROP delivered;

ALTER TABLE food_request ADD COLUMN school_fk VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci;

ALTER TABLE food_request
ADD CONSTRAINT food_request_school_fk
FOREIGN KEY (school_fk) REFERENCES school_identification(inep_id)
ON DELETE NO ACTION
ON UPDATE CASCADE;

-- =====================================================================================================

-- CRIAR O EDCENSO_ALIAS 2024 PRA IMPORTAR OS BANCOS AUXILIARES

insert into edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
select register, corder, attr, cdesc, `default`, stable, '2024' from edcenso_alias where `year` = '2023';

UPDATE `edcenso_alias` SET
`register` = '301',
`corder` = '98',
`attr` = 'id_email',
`cdesc` = 'Endereço Eletrônico (e-mail)',
`default` = NULL,
`stable` = 'STUDENT_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '301' AND `corder` = '98' AND `attr` = 'id_email' AND `attr` = 'id_email' COLLATE utf8mb4_bin AND `cdesc` = 'Endereço Eletrônico (e-mail)' AND `cdesc` = 'Endereço Eletrônico (e-mail)' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '301',
`corder` = '19',
`attr` = 'deficiency_type_monocular_vision',
`cdesc` = 'Visão Monocular',
`default` = NULL,
`stable` = 'STUDENT_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '301' AND `corder` = '19' AND `attr` = 'deficiency_type_monocular_vision' AND `attr` = 'deficiency_type_monocular_vision' COLLATE utf8mb4_bin AND `cdesc` = 'Visão Monocular' AND `cdesc` = 'Visão Monocular' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '302',
`corder` = '5',
`attr` = 'cpf',
`cdesc` = 'Número do CPF',
`default` = NULL,
`stable` = NULL,
`year` = '2024'
WHERE `register` = '302' AND `corder` = '5' AND `attr` = 'cpf' AND `attr` = 'cpf' COLLATE utf8mb4_bin AND `cdesc` = 'Número do CPF' AND `cdesc` = 'Número do CPF' COLLATE utf8mb4_bin AND `stable` = 'INSTRUCTOR_IDENTIFICATION' AND `stable` = 'INSTRUCTOR_IDENTIFICATION' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '302',
`corder` = '98',
`attr` = 'email',
`cdesc` = 'Endereço Eletrônico (e-mail)',
`default` = NULL,
`stable` = 'INSTRUCTOR_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '302' AND `corder` = '98' AND `attr` = 'email' AND `attr` = 'email' COLLATE utf8mb4_bin AND `cdesc` = 'Endereço Eletrônico (e-mail)' AND `cdesc` = 'Endereço Eletrônico (e-mail)' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '302',
`corder` = '19',
`attr` = 'deficiency_type_monocular_vision',
`cdesc` = 'Visão Monocular',
`default` = NULL,
`stable` = 'INSTRUCTOR_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '302' AND `corder` = '19' AND `attr` = 'deficiency_type_monocular_vision' AND `attr` = 'deficiency_type_monocular_vision' COLLATE utf8mb4_bin AND `cdesc` = 'Visão Monocular' AND `cdesc` = 'Visão Monocular' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;

-- COMO AS TURMAS, ALUNOS E PROFESSORES PODEM NÃO TER INEP_ID, PRECISA-SE DE COLUNA AUXILIAR PARA MAPEAR OS REGISTROS 50 E 60

ALTER TABLE `classroom`
ADD `censo_own_system_code` varchar(20) NULL;

ALTER TABLE `student_identification`
ADD `censo_own_system_code` varchar(20) NULL;

ALTER TABLE `instructor_identification`
ADD `censo_own_system_code` varchar(20) NULL;


-- =====================================================================================================

ALTER TABLE food_menu ADD adjusted BOOL DEFAULT 0 NULL;

-- =====================================================================================================

ALTER TABLE food_menu DROP FOREIGN KEY stage_food_menu;
ALTER TABLE food_menu DROP INDEX stage_food_menu;
ALTER TABLE food_menu DROP COLUMN stage_fk;
