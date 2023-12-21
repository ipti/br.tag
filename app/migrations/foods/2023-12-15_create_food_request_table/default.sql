CREATE TABLE food_request (
    id INT(11) NOT NULL AUTO_INCREMENT,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    food_fk INT(11),
    amount FLOAT,
    measurementUnit ENUM('g','Kg','l','pacote','unidade') NULL,
    description varchar(100) NULL,
    delivered BOOL NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    CONSTRAINT fk_food_fk
        FOREIGN KEY (food_fk)
        REFERENCES food(id)
);
