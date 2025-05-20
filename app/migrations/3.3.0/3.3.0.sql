-- alterações do censo no registro 30

ALTER TABLE student_identification
ADD COLUMN id_indigenous_people VARCHAR(8) NULL;

ALTER TABLE instructor_identification
ADD COLUMN id_indigenous_people VARCHAR(8) NULL;

UPDATE edcenso_alias
SET attr = 'id_indigenous_people'
WHERE corder = 13 and year = 2025 and cdesc = "Povo indígena";
