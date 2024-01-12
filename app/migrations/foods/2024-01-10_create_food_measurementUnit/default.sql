ALTER TABLE food ADD measurementUnit ENUM('g','l','u') NULL;

UPDATE food
SET measurementUnit = 'g'
WHERE category NOT IN ('Gorduras e óleos', 'Leite e derivados', 'Bebidas (alcoólicas e não alcoólicas)');


UPDATE food
SET measurementUnit = 'l'
WHERE category IN ('Gorduras e óleos', 'Leite e derivados', 'Bebidas (alcoólicas e não alcoólicas)');

UPDATE food
SET measurementUnit = 'u'
WHERE category IN ('Frutas e derivados');

UPDATE food SET measurementUnit = 'g' WHERE id IN (261, 262, 263, 264, 265, 266, 461, 462, 463, 464, 465, 466, 467, 468, 469);

UPDATE food SET measurementUnit = 'l' WHERE id IN (188, 209, 213, 215, 217, 218, 219, 234, 252, 258, 523, 211);
