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
