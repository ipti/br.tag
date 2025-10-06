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
