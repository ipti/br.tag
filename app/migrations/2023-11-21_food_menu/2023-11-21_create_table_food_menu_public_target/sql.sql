CREATE TABLE food_menu_vs_food_public_target (
	id INT auto_increment NOT NULL,
	food_menu_fk INT NULL,
	food_public_target_fk INT NULL,
	CONSTRAINT food_menu_vs_food_public_target_PK PRIMARY KEY (id),
	CONSTRAINT food_menu_vs_food_public_target_UN UNIQUE KEY (id),
	CONSTRAINT food_menu_vs_food_public_target_FK FOREIGN KEY (food_menu_fk) REFERENCES food_menu(id),
	CONSTRAINT food_menu_vs_food_public_target_FK_1 FOREIGN KEY (food_public_target_fk) REFERENCES food_public_target(id)
)
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;
