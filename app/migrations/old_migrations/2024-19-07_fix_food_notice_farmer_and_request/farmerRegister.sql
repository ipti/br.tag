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
