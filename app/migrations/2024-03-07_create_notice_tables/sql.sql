CREATE TABLE food_notice (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
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