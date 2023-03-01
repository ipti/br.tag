CREATE TABLE `instructor_faults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instructor_fk` int(11) NOT NULL,
  `schedule_fk` int(11) NOT NULL,
  `justification` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_instructor_faults_2` (`instructor_fk`),
  KEY `instructor_faults_FK` (`schedule_fk`),
  CONSTRAINT `instructor_faults_FK` FOREIGN KEY (`schedule_fk`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `instructor_faults_FK_1` FOREIGN KEY (`instructor_fk`) REFERENCES `instructor_teaching_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=485 DEFAULT CHARSET=latin1;