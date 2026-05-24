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
