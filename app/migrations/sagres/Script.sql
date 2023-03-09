-- `io.escola.demo`.attendance definition

CREATE TABLE `attendance` (
  `id_attendance` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `local` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `professional_fk` int(11) NOT NULL,
  PRIMARY KEY (`id_attendance`),
  KEY `attendance_FK` (`professional_fk`),
  KEY `attendance_FK_1` (`local`),
  CONSTRAINT `attendance_FK` FOREIGN KEY (`professional_fk`) REFERENCES `professional` (`id_professional`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO attendance VALUES (1, curdate(), 'Itabaiana', 1);

-- `io.escola.demo`.professional definition

CREATE TABLE `professional` (
  `id_professional` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `cpf_professional` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `speciality_fk` int(11) NOT NULL,
  `inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `fundeb` tinyint(1) NOT NULL,
  `attendance_fk` int(11) NOT NULL,
  PRIMARY KEY (`id_professional`),
  KEY `professional_FK` (`inep_id_fk`),
  KEY `professional_FK_1` (`speciality_fk`),
  KEY `professional_FK_2` (`attendance_fk`),
  CONSTRAINT `professional_FK` FOREIGN KEY (`inep_id_fk`) REFERENCES `school_identification` (`inep_id`),
  CONSTRAINT `professional_FK_1` FOREIGN KEY (`speciality_fk`) REFERENCES `edcenso_professional_education_course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `professional_FK_2` FOREIGN KEY (`attendance_fk`) REFERENCES `attendance` (`id_attendance`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ALTER TABLE `student_enrollment` ADD `date_cancellation_enrollment` DATE NULL DEFAULT NULL AFTER `status`;
-- ALTER TABLE `school_identification` ADD `number_ato` VARCHAR(30) NOT NULL AFTER `final_date`;
-- ALTER TABLE `lunch_menu` ADD `adjusted` TINYINT NOT NULL AFTER date;


INSERT INTO professional VALUES (1, 'JOAO DA SILVA', '71685776035', '1001', '28022122', 1, 1);

-- UPDATE student_identification
-- SET birthday = STR_TO_DATE(birthday, '%Y-%m-%d');

-- `io.escola.demo`.provision_accounts definition

CREATE TABLE `provision_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cod_unidade_gestora` VARCHAR(30) NOT NULL,
  `name_unidade_gestora` VARCHAR(150) NOT NULL,
  `cpf_responsavel` VARCHAR(16) NOT NULL,
  `cpf_gestor` VARCHAR(16) NOT NULL,
  `ano_referencia` int(11) NOT NULL,
  `mes_referencia` int(11) NOT NULL,
  `versao_xml` int(11) NOT NULL,
  `dia_inicio_prest_contas` int(11) NOT NULL,
  `dia_final_prest_contas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
