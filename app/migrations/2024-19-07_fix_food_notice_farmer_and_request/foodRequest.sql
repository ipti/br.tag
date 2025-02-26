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
