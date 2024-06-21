INSERT INTO food_meal_type (description)
VALUES
  ('Café da Manhã'),
  ('Almoço'),
  ('Jantar'),
  ('Lanche da Tarde'),
  ('Ceia');
 
INSERT INTO food_public_target (id, name)
VALUES
  (1,'Creche (7-11 meses)'),
  (2, 'Creche (1-3 anos)'),
  (3, 'Pré-escola (3-5)'),
  (4, 'Ensino Fundamental (6-10)'),
  (5, 'Ensino Fundamental (11-15)'),
  (6, 'Ensino Médio'),
  (7, 'EJA')

INSERT
INTO
food_measurement (unit,value, measure)
VALUES
  ('concha pequena', 80.00,'ml'),   
  ('Colher de sopa',15,'ml'),   
  ('Cálice', 45,'ml'),   
  ('Caneca', 300,'ml'),   
  ('Dedo', 50,'ml'),   
  ('Dedo de copo',25,'ml'),   
  ('Dedo de caneca',60,'ml'),   
  ('Pitada',1.8,'ml');

INSERT INTO instance_config (parameter_key,parameter_name, value) VALUES 
('FEAT_FOOD', 'novo módulo de merenda', 0)