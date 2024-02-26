ALTER TABLE food_ingredient
DROP COLUMN food_id_fk;

ALTER TABLE food_ingredient
ADD COLUMN food_id_fk INT(11),
ADD CONSTRAINT fk_food_id
FOREIGN KEY (food_id_fk) REFERENCES food(id);