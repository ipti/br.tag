
-- `io.escola.demo`.professional definition
CREATE TABLE `professional` (
  `id_professional` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `cpf_professional` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `speciality_fk` int(11) NOT NULL,
  `inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `fundeb` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_professional`),
  KEY `professional_FK` (`inep_id_fk`),
  KEY `professional_FK_1` (`speciality_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- `io.escola.demo`.attendance definition
CREATE TABLE `attendance` (
  `id_attendance` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `local` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `professional_fk` int(11) NOT NULL,
  PRIMARY KEY (`id_attendance`),
  KEY `attendance_FK` (`professional_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `professional` ADD CONSTRAINT `professional_school_identification_fk` FOREIGN KEY (`inep_id_fk`) REFERENCES `school_identification` (`inep_id`);
ALTER TABLE `professional` ADD CONSTRAINT `professional_edcenso_professional_education_course_fk` FOREIGN KEY (`speciality_fk`) REFERENCES `edcenso_professional_education_course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `attendance` ADD CONSTRAINT `attendance_professional_fk` FOREIGN KEY (`professional_fk`) REFERENCES `professional` (`id_professional`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `student_enrollment` ADD `date_cancellation_enrollment` DATE NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `school_identification` ADD `number_ato` VARCHAR(30) NOT NULL AFTER `final_date`;
ALTER TABLE `lunch_menu` ADD `adjusted` TINYINT NOT NULL AFTER date;

UPDATE student_identification SET birthday = DATE_FORMAT(STR_TO_DATE(birthday, '%d/%m/%Y'), '%Y-%m-%d');

-- `io.escola.demo`.provision_accounts definition
CREATE TABLE `provision_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codigoUnidGestora` varchar(30) NOT NULL,
  `nomeUnidGestora` varchar(150) NOT NULL,
  `cpfResponsavel` varchar(16) NOT NULL,
  `cpfGestor` varchar(16) NOT NULL,
  `anoReferencia` int(11) NOT NULL,
  `mesReferencia` int(11) NOT NULL,
  `versaoxml` int(11) NOT NULL,
  `diaInicPresContas` int(11) NOT NULL,
  `diaFinaPresContas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
