-- `io.escola.demo`.student_restrictions definition

CREATE TABLE `student_restrictions` (
  `student_fk` int(11) NOT NULL,
  `celiac` tinyint(1) NOT NULL,
  `diabetes` tinyint(1) NOT NULL,
  `hypertension` tinyint(1) NOT NULL,
  `iron_deficiency_anemia` tinyint(1) NOT NULL,
  `sickle_cell_anemia` tinyint(1) NOT NULL,
  `lactose_intolerance` tinyint(1) NOT NULL,
  `malnutrition` tinyint(1) NOT NULL,
  `obesity` tinyint(1) NOT NULL,
  `others` varchar(200) NULL;
  KEY `student_food_restrictions_FK` (`student_fk`),
  CONSTRAINT `student_food_restrictions_FK` FOREIGN KEY (`student_fk`) REFERENCES `student_identification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `io.escola.demo`.student_identification DROP COLUMN food_restrictions;
