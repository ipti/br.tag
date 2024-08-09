ALTER TABLE food_measurement
DROP COLUMN unit;

ALTER TABLE food_measurement
ADD unit varchar(100);

delete from food_measurement;

ALTER TABLE food_measurement
MODIFY COLUMN measure ENUM('Kg','mg','g','ml','L', 'u');

insert into food_measurement (value, unit, measure) values
(240, "xícara", "ml"),
(1, "l (litro)", "l"),
(1, "kg (kilograma)", "kg"),
(1, "g (grama)", "g"),
(1, "ml (mililitro)", "ml"),
(1, "unidade", "u"),
(300, "copo (requeijão) duplo colmado (cheio)", "ml"),
(15, "colher (sopa)", "ml"),
(5, "colher (chá)", "ml"),
(50, "dedo", "ml"),
(25, "dedo de copo (americano)", "ml"),
(40, "dedo de copo duplo (requeijão)", "ml"),
(60, "dedo de caneca", "ml"),
(0.625, "dedo de caneca", "ml"),
(175, "copo (americano) até o vinco", "ml"),
(200, "copo (americano) cheio", "ml"),
(250, "copo (americano) duplo até o vinco", "ml"),
(150, "copo (requeijão) pequeno", "ml"),
(250, "copo (requeijão) duplo nivelado", "ml"),
(90, "copo (vinho)", "ml"),
(45, "cálice", "ml"),
(120, "1/2 xícara", "ml"),
(80, "1/3 xícara", "ml"),
(60, "1/4 xícara", "ml"),
(300, "caneca", "ml"),
(40, "xícara (café)", "ml"),
(22.5, "colher de pau", "ml"),
(30, "colher de servir ou 1 colher (arroz)", "ml"),
(7.5, "colher (sobremesa)", "ml"),
(2.5, "colher (café)", "ml"),
(80, "concha pequena", "ml"),
(160, "concha média", "ml"),
(320, "concha grande", "ml");


ALTER TABLE food_ingredient
MODIFY COLUMN amount DECIMAL(10,2);
