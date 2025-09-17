ALTER TABLE food_menu
ADD COLUMN include_saturday TINYINT(1) DEFAULT 0 NULL;

ALTER TABLE food_ingredient
ADD COLUMN measurement_for_unit ENUM('g', 'ml', 'Kg', 'L');

ALTER TABLE food_ingredient
ADD COLUMN amount_for_unit INT NULL;
