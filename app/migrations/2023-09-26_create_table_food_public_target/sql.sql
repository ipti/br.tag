CREATE TABLE food_public_target (
	name varchar(100) NULL,
	id INT auto_increment NOT NULL,
	CONSTRAINT food_public_target_PK PRIMARY KEY (id),
	CONSTRAINT food_public_target_UN UNIQUE KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;
