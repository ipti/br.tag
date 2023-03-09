CREATE TABLE `attendance` (
     `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
     `date` DATE NOT NULL,
     `local` VARCHAR(100) NOT NULL,
     `professional_fk` INT NOT NULL
);

CREATE TABLE `professional` (
     `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
     `name` VARCHAR(200) NOT NULL,
     `cpf` VARCHAR(14) NOT NULL,
     `speciality` VARCHAR(100) NOT NULL,
     `inep_id_fk` VARCHAR(8) NOT NULL,
     `fundeb` BOOLEAN NOT NULL
);


ALTER TABLE attendance CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE attendance ADD CONSTRAINT fk_professional_attendance FOREIGN KEY (professional_fk) REFERENCES professional(id);

ALTER TABLE professional CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE professional ADD CONSTRAINT fk_schoolidentificationProfessional FOREIGN KEY (inep_id_fk) REFERENCES school_identification(inep_id);

-- ALTER TABLE `student_enrollment` ADD `date_cancellation_enrollment` DATE NULL DEFAULT NULL AFTER `status`;
-- ALTER TABLE `school_identification` ADD `number_ato` VARCHAR(30) NOT NULL AFTER `final_date`;
-- ALTER TABLE `lunch_menu` ADD `adjusted` TINYINT NOT NULL AFTER date;


INSERT INTO professional VALUES (1, 'JOAO DA SILVA', '71685776035', 'Médico', '28022041', 1);
INSERT INTO attendance VALUES (1, curdate(), 'Itabaiana', 1);

-- UPDATE student_identification
-- SET birthday = STR_TO_DATE(birthday, '%Y-%m-%d');

`io.escola.demo`.provision_accounts definition

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
