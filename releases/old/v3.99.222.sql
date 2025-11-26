-- app/migrations/2024-03-11_fix_food_request/default.sql

ALTER TABLE food_request ADD status ENUM('Em andamento','Finalizado') DEFAULT 'Em andamento';

ALTER TABLE food_request DROP delivered;

ALTER TABLE food_request ADD COLUMN school_fk VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci;

ALTER TABLE food_request
ADD CONSTRAINT food_request_school_fk
FOREIGN KEY (school_fk) REFERENCES school_identification(inep_id)
ON DELETE NO ACTION
ON UPDATE CASCADE;

ALTER TABLE farmer_foods ADD deliveredAmount float DEFAULT 0;

-- app/migrations/2024-04-02_add_notice_on_farmer_foods/default.sql

ALTER TABLE farmer_foods ADD foodNotice_fk int(11) NULL;

ALTER TABLE farmer_foods
ADD CONSTRAINT fk_farmer_foods_food_notice
FOREIGN KEY (foodNotice_fk) REFERENCES food_notice(id);

ALTER TABLE food_request add farmer_fk int(11) NOT NULL;

ALTER TABLE food_request
ADD CONSTRAINT fk_food_request_farmer
FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id);

ALTER TABLE food_notice_item ADD foodNotice_fk int(11) NULL;

ALTER TABLE food_notice_item
ADD CONSTRAINT fk_food_notice_item_food_notice
FOREIGN KEY (foodNotice_fk) REFERENCES food_notice(id);

DROP TABLE food_notice_vs_food_notice_item;

-- app/migrations/2024-05-13_refactor_food_request/default.sql

ALTER TABLE food_request DROP FOREIGN KEY food_request_ibfk_1;
ALTER TABLE food_request DROP FOREIGN KEY food_request_school_fk;
ALTER TABLE food_request DROP FOREIGN KEY fk_food_request_farmer;

ALTER TABLE food_request DROP COLUMN food_fk;
ALTER TABLE food_request DROP COLUMN school_fk;
ALTER TABLE food_request DROP COLUMN farmer_fk;
ALTER TABLE food_request DROP COLUMN amount;
ALTER TABLE food_request DROP COLUMN measurementUnit;
ALTER TABLE food_request DROP COLUMN description;

ALTER TABLE food_request ADD COLUMN notice_fk int(11),
ADD CONSTRAINT fk_notice_fk
FOREIGN KEY (notice_fk) REFERENCES food_notice(id);

ALTER TABLE food_request ADD COLUMN reference_id varchar(36) NULL;

CREATE TABLE food_request_vs_farmer_register (
    id INT(11) NOT NULL AUTO_INCREMENT,
    farmer_fk INT(11),
    food_request_fk INT(11),
    PRIMARY KEY (id)
);

ALTER TABLE food_request_vs_farmer_register
ADD CONSTRAINT fk_farmer_fk
FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id);

ALTER TABLE food_request_vs_farmer_register
ADD CONSTRAINT fk_food_request_fk
FOREIGN KEY (food_request_fk) REFERENCES food_request(id);

CREATE TABLE food_request_vs_school_identification (
    id INT(11) NOT NULL AUTO_INCREMENT,
    school_fk VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci,
    food_request_fk INT(11),
    PRIMARY KEY (id)
);

ALTER TABLE food_request_vs_school_identification
ADD CONSTRAINT school_food_request_fk
FOREIGN KEY (food_request_fk) REFERENCES food_request(id);

ALTER TABLE food_request_vs_school_identification
ADD CONSTRAINT food_request_school_fk
FOREIGN KEY (school_fk) REFERENCES school_identification(inep_id)
ON DELETE NO ACTION
ON UPDATE CASCADE;

CREATE TABLE food_request_item (
    id INT(11) NOT NULL AUTO_INCREMENT,
    food_fk INT(11),
    amount FLOAT,
    measurementUnit ENUM('g','Kg','l','pacote','unidade') NULL,
    food_request_fk INT(11),
    PRIMARY KEY (id),
    CONSTRAINT fk_food_fk
        FOREIGN KEY (food_fk)
        REFERENCES food(id)
);

ALTER TABLE food_request_item
ADD CONSTRAINT item_food_request_fk
FOREIGN KEY (food_request_fk) REFERENCES food_request(id);

-- app/migrations/2024-06-06_add_status_on_farmer_register/default.sql

ALTER TABLE farmer_register ADD status enum('Inativo','Ativo') default 'Ativo' NULL;

ALTER TABLE food_notice ADD status enum('Inativo','Ativo') default 'Ativo' NULL;

ALTER TABLE food_notice ADD reference_id varchar(36) NULL;

ALTER TABLE food_notice ADD file_name varchar(100) NULL;

-- app/migrations/2024-10-23_create_substitute_teacher_table/sql.sql

CREATE TABLE substitute_instructor(
    id int NOT NULL AUTO_INCREMENT,
    instructor_fk int NOT NULL,
    teaching_data_fk int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (instructor_fk) REFERENCES instructor_identification(id)
);

ALTER TABLE schedule ADD substitute_instructor_fk int,
ADD CONSTRAINT fk_substitute_instructor FOREIGN KEY (substitute_instructor_fk) REFERENCES substitute_instructor(id);

-- app/migrations/2024-19-07_fix_food_notice_farmer_and_request/farmerRegister.sql

CREATE TABLE farmer_register (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    cpf varchar(11) NOT NULL,
    phone varchar(11) NULL,
    group_type enum('Fornecedor Individual','Grupo Formal','Grupo Informal') NULL,
    reference_id varchar(36) NULL,
    status enum('Inativo', 'Ativo') DEFAULT 'Ativo',
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE farmer_foods (
    id INT(11) NOT NULL AUTO_INCREMENT,
    food_fk int(11) NULL,
    farmer_fk int(11) NULL,
    measurementUnit enum('g','Kg','l','pacote','unidade') NULL,
    amount FLOAT NOT NULL,
    foodNotice_fk int(11) NULL,
    deliveredAmount FLOAT NULL default 0,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_farmer_foods_food_notice FOREIGN KEY (foodNotice_fk) REFERENCES food_notice(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_farmer_foods_food FOREIGN KEY (food_fk) REFERENCES food(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_farmer_foods_farmer_register FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);


-- app/migrations/2024-19-07_fix_food_notice_farmer_and_request/foodNotice.sql

DROP TABLE IF EXISTS `food_notice_vs_food_notice_item`;
DROP TABLE IF EXISTS `food_notice_item`;
DROP TABLE IF EXISTS `food_notice`;
DROP TABLE IF EXISTS `farmer_foods`;
DROP TABLE IF EXISTS `farmer_register`;
DROP TABLE IF EXISTS `food_request_vs_school_identification`;
DROP TABLE IF EXISTS `food_request_vs_farmer_register`;
DROP TABLE IF EXISTS `food_request_item_received`;
DROP TABLE IF EXISTS `food_request_item`;
DROP TABLE IF EXISTS `food_request`;

CREATE TABLE food_notice (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    date date NOT NULL,
    status enum('Inativo', 'Ativo') DEFAULT 'Ativo',
    reference_id varchar(36) NULL,
    file_name varchar(100) NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE food_notice_item (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    description varchar(1000) NULL,
    measurement varchar(20) NOT NULL,
    year_amount varchar(20) NULL,
    food_id int(11) NULL,
    foodNotice_fk int(11) NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_food_notice_item_food_notice FOREIGN KEY (foodNotice_fk) REFERENCES food_notice(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_food_notice_item_food FOREIGN KEY (food_id) REFERENCES food(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);

-- app/migrations/2024-19-07_fix_food_notice_farmer_and_request/foodRequest.sql

CREATE TABLE food_request (
    id INT(11) NOT NULL AUTO_INCREMENT,
    date datetime NULL DEFAULT CURRENT_TIMESTAMP,
    status enum('Em andamento','Finalizado') NULL default 'Em andamento',
    notice_fk int(11) NULL,
    reference_id varchar(36) NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_food_request_food_notice FOREIGN KEY (notice_fk) REFERENCES food_notice(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);

CREATE TABLE food_request_item (
    id INT(11) NOT NULL AUTO_INCREMENT,
    food_fk int(11) NULL,
    amount float NULL,
    measurementUnit enum('g','Kg','l','pacote','unidade') NULL,
    food_request_fk int(11) NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_food_request_item_food FOREIGN KEY (food_fk) REFERENCES food(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_food_request_item_food_request FOREIGN KEY (food_request_fk) REFERENCES food_request(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);

CREATE TABLE food_request_item_received (
    id INT(11) NOT NULL AUTO_INCREMENT,
    food_fk int(11) NULL,
    farmer_fk int(11) NULL,
    food_request_fk int(11) NULL,
    amount float NULL,
    measurementUnit enum('g','Kg','l','pacote','unidade') NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    date datetime NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_food_request_item_received_food_fk FOREIGN KEY (food_fk) REFERENCES food(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_food_request_item_received_farmer_fk FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_food_request_item_received_food_requestfk FOREIGN KEY (food_request_fk) REFERENCES food_request(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);

CREATE TABLE food_request_vs_farmer_register (
    id INT(11) NOT NULL AUTO_INCREMENT,
    farmer_fk int(11) NULL,
    food_request_fk int(11) NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_farmer_fk FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_food_request_fk FOREIGN KEY (food_request_fk) REFERENCES food_request(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);

CREATE TABLE food_request_vs_school_identification (
    id INT(11) NOT NULL AUTO_INCREMENT,
    school_fk VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci,
    food_request_fk int(11) NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT food_request_school_fk FOREIGN KEY (school_fk) REFERENCES school_identification(inep_id)
    ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT school_food_request_fk FOREIGN KEY (food_request_fk) REFERENCES food_request(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);

-- app/migrations/2025-06-01_chageedcenso_stage_vs_modality_fk_on_grade_unity/2025-06-01_chageedcenso_stage_vs_modality_fk_on_grade_unity.sql


create table classroom_vs_grade_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_fk INT NOT NULL,
    grade_rules_fk INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT classroom_vs_grade_rules_fk_classroom FOREIGN KEY (classroom_fk) REFERENCES classroom(id),
    CONSTRAINT classroom_vs_grade_rules_fk_grade_rules FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id)
);

ALTER TABLE grade_rules ADD COLUMN name varchar(100) NULL;

UPDATE grade_rules gr
join edcenso_stage_vs_modality esvm on gr.edcenso_stage_vs_modality_fk = esvm.id
SET gr.name = CONCAT('Estrutura de unidades da etapa ', esvm.name);

ALTER TABLE grade_unity ADD COLUMN grade_rules_fk int null;

ALTER TABLE grade_unity
ADD CONSTRAINT fk_grade_rules
FOREIGN KEY (grade_rules_fk)
REFERENCES grade_rules(id);

UPDATE grade_unity gu
join grade_rules gr on gr.edcenso_stage_vs_modality_fk = gu.edcenso_stage_vs_modality_fk
SET gu.grade_rules_fk = gr.id;


CREATE TABLE grade_rules_vs_edcenso_stage_vs_modality (
    id INT AUTO_INCREMENT PRIMARY KEY,
    edcenso_stage_vs_modality_fk INT NOT NULL,
    grade_rules_fk INT NOT NULL,
    FOREIGN KEY (edcenso_stage_vs_modality_fk) REFERENCES edcenso_stage_vs_modality(id),
    FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id)
);

CREATE INDEX idx_edcenso_stage_vs_modality_fk
ON grade_rules_vs_edcenso_stage_vs_modality (edcenso_stage_vs_modality_fk);

CREATE INDEX idx_grade_rules_fk
ON grade_rules_vs_edcenso_stage_vs_modality (grade_rules_fk);


INSERT INTO grade_rules_vs_edcenso_stage_vs_modality (edcenso_stage_vs_modality_fk, grade_rules_fk)
SELECT
    e.id AS edcenso_stage_vs_modality_fk,
    g.id AS grade_rules_fk
FROM
    edcenso_stage_vs_modality e
JOIN
    grade_rules g
ON
    e.id= g.edcenso_stage_vs_modality_fk ;

ALTER TABLE grade_rules MODIFY edcenso_stage_vs_modality_fk int NULL;
ALTER TABLE grade_unity MODIFY edcenso_stage_vs_modality_fk int NULL;

insert into classroom_vs_grade_rules (classroom_fk, grade_rules_fk) select c.id, gr.id
from classroom c
JOIN grade_rules gr ON c.edcenso_stage_vs_modality_fk = gr.edcenso_stage_vs_modality_fk;

INSERT INTO classroom_vs_grade_rules (classroom_fk, grade_rules_fk)
SELECT DISTINCT se.classroom_fk, gr.id
FROM  student_enrollment se
JOIN grade_rules gr ON se.edcenso_stage_vs_modality_fk = gr.edcenso_stage_vs_modality_fk;


-- app/migrations/2025-17-02_insert_flag_oninstance_config_has_individual_record/2025-17-02_insert_flag_oninstance_config_has_individual_record.sql

INSERT INTO `instance_config` (`parameter_key`, `parameter_name`, `value`)
VALUES ('HAS_INDIVIDUALRECORD', 'relatório de ficha individual do aluno', '0');

ALTER TABLE instance_config
ADD COLUMN created_at DATETIME DEFAULT NULL,
ADD COLUMN updated_at DATETIME DEFAULT NULL;

-- app/migrations/2025-29-1-add_cpf_reason_in_document_and_address/2025-29-1-add_cpf_reason_in_document_and_address.sql

ALTER TABLE student_documents_and_address ADD cpf_reason SMALLINT DEFAULT 0 NOT NULL;

UPDATE
	student_documents_and_address
SET
	cpf_reason = CASE
        WHEN cpf IS NULL THEN 0
		ELSE 4
	END;


insert into instance_config (parameter_key, parameter_name, value) values
('FEAT_DASHBOARD_POWER', 'Integração com power bi', 0)
