CREATE TABLE farmer_register (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    cpf varchar(11) NOT NULL,
    phone varchar(11) NULL,
    group_type ENUM('Fornecedor Individual','Grupo Formal','Grupo Informal',) NULL,
);

CREATE TABLE farmer_foods (
    id INT(11) NOT NULL AUTO_INCREMENT,
    food_fk INT(11),
    farmer_fk INT(11),
    measurementUnit ENUM('g','Kg','l','pacote','unidade') NULL,
    amount FLOAT NOT NULL,
)
