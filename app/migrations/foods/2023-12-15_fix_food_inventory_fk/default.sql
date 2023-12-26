-- Modificando as chaves estrangeiras de food_inventory
ALTER TABLE food ADD PRIMARY KEY (id);
ALTER TABLE food_inventory ADD status ENUM('Disponivel','Acabando','Emfalta') DEFAULT 'Disponivel';

DROP INDEX food_inventory_school_fk_food_fk_key ON food_inventory;

ALTER TABLE food_inventory
ADD CONSTRAINT food_fk
FOREIGN KEY (food_fk) REFERENCES food(id)
ON DELETE RESTRICT
ON UPDATE RESTRICT;

ALTER TABLE food_inventory MODIFY COLUMN measurementUnit enum('g','Kg','l','pacote','unidade') NULL;

-- Modificando as chaves estrangeiras de food_inventory_received
ALTER TABLE food_inventory_received MODIFY COLUMN foodSource enum('Varejista','Agricultura_Familiar') NULL;

ALTER TABLE food_inventory_received ADD COLUMN food_fk int(11) NULL;

ALTER TABLE food_inventory_received
ADD CONSTRAINT food_inventory_received_food_fk
FOREIGN KEY (food_fk) REFERENCES food(id)
ON DELETE RESTRICT
ON UPDATE RESTRICT;

-- Modificando as chaves estrangeiras de food_inventory_spent
ALTER TABLE food_inventory_spent ADD COLUMN food_fk int(11) NULL;

ALTER TABLE food_inventory_spent
ADD CONSTRAINT food_inventory_spent_food_fk
FOREIGN KEY (food_fk) REFERENCES food(id)
ON DELETE RESTRICT
ON UPDATE RESTRICT;
