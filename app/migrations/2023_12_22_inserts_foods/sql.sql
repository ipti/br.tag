INSERT INTO food_meal_type (description)
VALUES
  ('Café da Manhã'),
  ('Almoço'),
  ('Jantar'),
  ('Lanche da Tarde'),
  ('Ceia');
 
INSERT INTO food_public_target (name)
VALUES
  ('Pré-escola (3-5)'),
  ('Ensino Fundamental (6-10)'),
  ('Ensino Médio (11-17)'),
  ('Universitários'),
  ('Adultos'),
  ('Idosos');

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