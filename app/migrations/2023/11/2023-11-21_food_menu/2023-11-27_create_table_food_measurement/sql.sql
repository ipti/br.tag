CREATE TABLE food_measurement (
	id INT auto_increment NOT NULL,
    unit ENUM('Concha', 'Colher', 'Cálice', 'Caneca', 'Dedo', 'Dedo de copo', 'Dedo de caneca', 'Pitada', 'Punhado') NOT NULL,
    value FLOAT NOT NULL,
	measure ENUM('Kg','mg','g','ml','L') NOT NULL,
    PRIMARY KEY(id)
   );
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;


ALTER TABLE food_ingredient DROP COLUMN measurementUnit;
ALTER TABLE food_menu_meal ADD COLUMN (meal_time TIME, turn VARCHAR(100));
ALTER TABLE food_ingredient ADD COLUMN (food_measurement_fk INT);
ALTER TABLE food_ingredient ADD FOREIGN KEY (food_measurement_fk) REFERENCES food_measurement(id);
