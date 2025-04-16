ALTER TABLE food_request MODIFY COLUMN status VARCHAR(100) NULL DEFAULT 'Em andamento';

CREATE TABLE food_request_item_accepted (
    id INT(11) NOT NULL AUTO_INCREMENT,
    food_fk int(11) NULL,
    farmer_fk int(11) NULL,
    food_request_fk int(11) NULL,
    amount float NULL,
    measurementUnit enum('g','Kg','l','pacote','unidade') NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    date datetime NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_food_request_item_accepted_food_fk FOREIGN KEY (food_fk) REFERENCES food(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_food_request_item_accepted_farmer_fk FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_food_request_item_accepted_food_requestfk FOREIGN KEY (food_request_fk) REFERENCES food_request(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    PRIMARY KEY (id)
);
