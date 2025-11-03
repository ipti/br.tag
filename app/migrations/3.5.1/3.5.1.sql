ALTER TABLE grade_unity
ADD COLUMN weight FLOAT NULL AFTER name;

INSERT INTO grade_calculation (name)
VALUES ('Subistituir Menor Nota');
