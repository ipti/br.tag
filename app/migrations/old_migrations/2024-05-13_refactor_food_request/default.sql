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
