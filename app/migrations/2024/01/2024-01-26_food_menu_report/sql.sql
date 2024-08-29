ALTER TABLE food_menu
ADD COLUMN week VARCHAR(1) NULL;


DELETE FROM food_public_target;

INSERT INTO food_public_target (id, name)
VALUES
  (1,'Creche (7-11 meses)'),
  (2, 'Creche (1-3 anos)'),
  (3, 'Pré-escola (3-5)'),
  (4, 'Ensino Fundamental (6-10)'),
  (5, 'Ensino Fundamental (11-15)'),
  (6, 'Ensino Médio'),
  (7, 'EJA')