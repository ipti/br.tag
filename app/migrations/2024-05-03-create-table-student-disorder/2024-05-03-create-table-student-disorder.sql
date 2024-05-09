CREATE TABLE `student_disorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_fk` int(11) NOT NULL,
  `tdah` tinyint(1) NOT NULL DEFAULT '0',
  `depressao` tinyint(1) NOT NULL DEFAULT '0',
  `tab` tinyint(1) NOT NULL DEFAULT '0',
  `toc` tinyint(1) NOT NULL DEFAULT '0',
  `tag` tinyint(1) NOT NULL DEFAULT '0',
  `tod` tinyint(1) NOT NULL DEFAULT '0',
  `tcne` tinyint(1) NOT NULL DEFAULT '0',
  `others` varchar(200)  NULL DEFAULT "",
  PRIMARY KEY (`id`),
  KEY `student_disorder_FK` (`student_fk`),
  CONSTRAINT `student_disorder_FK` FOREIGN KEY (`student_fk`) REFERENCES `student_identification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
