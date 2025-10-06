-- `io.escola.demo`.student_restrictions definition

CREATE TABLE `student_restrictions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_fk` int(11) NOT NULL,
  `celiac` tinyint(1) NOT NULL DEFAULT '0',
  `diabetes` tinyint(1) NOT NULL DEFAULT '0',
  `hypertension` tinyint(1) NOT NULL DEFAULT '0',
  `iron_deficiency_anemia` tinyint(1) NOT NULL DEFAULT '0',
  `sickle_cell_anemia` tinyint(1) NOT NULL DEFAULT '0',
  `lactose_intolerance` tinyint(1) NOT NULL DEFAULT '0',
  `malnutrition` tinyint(1) NOT NULL DEFAULT '0',
  `obesity` tinyint(1) NOT NULL DEFAULT '0',
  `others` varchar(200)  NULL DEFAULT "",
  PRIMARY KEY (`id`),
  KEY `student_food_restrictions_FK` (`student_fk`),
  CONSTRAINT `student_food_restrictions_FK` FOREIGN KEY (`student_fk`) REFERENCES `student_identification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO student_restrictions (student_fk, others)
SELECT id, food_restrictions
FROM student_identification;

UPDATE student_restrictions
SET others = ''
WHERE others = 'NAO' OR others = 'NENHUMA';