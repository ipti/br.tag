ALTER TABLE food_request ADD status ENUM('Em andamento','Finalizado') DEFAULT 'Em andamento';

ALTER TABLE food_request DROP delivered;

ALTER TABLE food_request ADD COLUMN school_fk VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci;

ALTER TABLE food_request
ADD CONSTRAINT food_request_school_fk
FOREIGN KEY (school_fk) REFERENCES school_identification(inep_id)
ON DELETE NO ACTION
ON UPDATE CASCADE;

ALTER TABLE farmer_foods ADD deliveredAmount float DEFAULT 0;
