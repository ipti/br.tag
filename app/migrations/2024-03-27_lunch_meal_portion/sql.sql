ALTER TABLE lunch_portion
ADD COLUMN food_fk INT NOT NULL;

ALTER TABLE lunch_portion
ADD CONSTRAINT fk_lunch_portion_food
FOREIGN KEY (food_fk) REFERENCES food(id);

ALTER TABLE lunch_portion
DROP FOREIGN KEY portion_unity;

ALTER TABLE lunch_portion
ADD CONSTRAINT fk_lunch_unity_fk
FOREIGN KEY (unity_fk) REFERENCES food_measurement(id);


ALTER TABLE lunch_portion
DROP FOREIGN KEY portion_item;

ALTER TABLE lunch_portion DROP COLUMN item_fk;

ALTER TABLE lunch_menu_meal DROP COLUMN amount;
