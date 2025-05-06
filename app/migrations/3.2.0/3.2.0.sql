-- `demo.tag.ong.br`.online_enrollment_student_identification definition
CREATE TABLE
  IF NOT EXISTS `enrollment_online_student_identification` (
    `classroom_inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
    `classroom_fk` int (11) DEFAULT NULL,
    `id` int (11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `user_fk` INT(11) DEFAULT NULL,
    `birthday` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
    `cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
    `sex` smallint (6) NOT NULL,
    `color_race` smallint (6) NOT NULL,
    `deficiency` tinyint (1) NOT NULL,
    `deficiency_type_blindness` tinyint (1) DEFAULT NULL,
    `deficiency_type_low_vision` tinyint (1) DEFAULT NULL,
    `deficiency_type_deafness` tinyint (1) DEFAULT NULL,
    `deficiency_type_disability_hearing` tinyint (1) DEFAULT NULL,
    `deficiency_type_deafblindness` tinyint (1) DEFAULT NULL,
    `deficiency_type_phisical_disability` tinyint (1) DEFAULT NULL,
    `deficiency_type_intelectual_disability` tinyint (1) DEFAULT NULL,
    `deficiency_type_multiple_disabilities` tinyint (1) DEFAULT NULL,
    `deficiency_type_autism` tinyint (1) DEFAULT NULL,
    `deficiency_type_gifted` tinyint (1) DEFAULT NULL,
    `last_change` timestamp NULL DEFAULT NULL,
    `mother_name` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
    `father_name` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
    `responsable_name` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
    `responsable_cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
    `responsable_telephone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
    `cep` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
    `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
    `number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
    `complement` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
    `neighborhood` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
    `zone` smallint (6) DEFAULT NULL,
    `edcenso_city_fk` int (11) DEFAULT NULL,
    `edcenso_uf_fk` int (11) DEFAULT NULL,
    `unavailable` tinyint (1) NOT NULL DEFAULT '0',
    `student_fk` int (11) DEFAULT NULL,
    `edcenso_stage_vs_modality_fk` int (11) DEFAULT NULL,
    `event_pre_registration_fk` int (11) DEFAULT NULL,
    `stages_vacancy_pre_registration_fk` int (11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `id` (`id`),
    KEY `fk_student_pre_identification_1` (`classroom_fk`),
    KEY `school_inep_id_fk` (`school_inep_id_fk`),
    KEY `fk_student_pre_identification_2` (`edcenso_city_fk`),
    KEY `fk_student_pre_identification_3` (`edcenso_uf_fk`),
    KEY `fk_student_pre_identification_5` (`status_fk`),
    KEY `fk_student_pre_identification_6` (`student_fk`),
    KEY `event_pre_registration_fk` (`event_pre_registration_fk`),
    KEY `edcenso_stage_vs_modality_fk` (`edcenso_stage_vs_modality_fk`),
    KEY `stages_vacancy_pre_registration_fk` (`stages_vacancy_pre_registration_fk`),
    CONSTRAINT `fk_oesi_stage_modality` FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_oesi_event_pre_reg` FOREIGN KEY (`event_pre_registration_fk`) REFERENCES `event_pre_registration` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_oesi_classroom` FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `fk_oesi_city` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`),
    CONSTRAINT `fk_oesi_user` FOREIGN KEY (`user_fk`) REFERENCES `users`(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_oesi_student` FOREIGN KEY (`student_fk`) REFERENCES `student_identification` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_oesi_stage_vacancy` FOREIGN KEY (`stages_vacancy_pre_registration_fk`) REFERENCES `stages_vacancy_pre_registration` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE
  `enrollment_online_enrollment_solicitation` (
    `id` int (11) NOT NULL AUTO_INCREMENT,
    `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
    `enrollment_online_student_identification_fk` int (11) COLLATE utf8_unicode_ci NOT NULL,
    `status` INT (11),
    PRIMARY KEY (`id`),
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    KEY `fk_enrollment_online_enrollment_solicitation_1_idx` (`school_inep_id_fk`),
    CONSTRAINT `fk_enrollment_online_enrollment_solicitation_1` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `fk_enrollment_online_enrollment_solicitation_2` FOREIGN KEY (`enrollment_online_student_identification_fk`) REFERENCES `enrollment_online_student_identification` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
  ) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

insert into
  auth_item (name, type, description, bizrule, data)
values
  ('guardian', 2, null, null, 'N;');

ALTER TABLE users
MODIFY COLUMN role ENUM('USER','ADMIN','INSTRUCTOR','MANAGER','NUTRITIONIST','GUARDIAN') NULL;
