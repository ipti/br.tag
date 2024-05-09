insert into lunch_unity (name, acronym) values
("Grama", "G"),
("Mililitro", "ML"),
("Unidade", "U"),
("Duzia", "DZ"),
("Pacote", "PCT");

UPDATE lunch_unity
SET acronym = 'L'
WHERE acronym = 'ML' AND name = 'litro';

UPDATE lunch_unity
SET acronym = 'ML'
WHERE acronym = 'mL' AND name = 'Mililitro';


-- ==========================================================================

ALTER TABLE farmer_register ADD COLUMN reference_id varchar(36);
