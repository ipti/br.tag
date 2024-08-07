-- CRIAR TABELA DE GESTOR ESCOLAR PRA EVITAR EXCEPTION NA TELA DE EDUCACENSO


CREATE TABLE IF NOT EXISTS `manager_identification` (
	`register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '30',
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`school_inep_id_fk` VARCHAR(8) COLLATE utf8_unicode_ci NOT NULL,
	`inep_id` VARCHAR(12) NULL,
	`name` VARCHAR(100) NOT NULL,
	`email` VARCHAR(100) NULL,
	`birthday_date` VARCHAR(10) NULL,
	`sex` SMALLINT(6) NULL,
	`color_race` SMALLINT(6) NULL,
	`nationality` SMALLINT(6) NULL,
	`role` int(11) DEFAULT NULL,
	`residence_zone` smallint(6)  NULL,
	`access_criterion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  	`contract_type` smallint(6) DEFAULT NULL,
  	`cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  	`number_ato` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  	`filiation` smallint(6)  NULL,
	`filiation_1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
	`filiation_2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
	`filiation_1_rg` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
	`filiation_1_cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
	`filiation_1_scholarity` smallint(6) DEFAULT NULL,
	`filiation_1_job` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
	`filiation_2_rg` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
	`filiation_2_cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
	`filiation_2_scholarity` smallint(6) DEFAULT NULL,
	`filiation_2_job` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
	`edcenso_nation_fk` INT(11) NULL,
  	`edcenso_uf_fk` INT(11) DEFAULT NULL,
  	`edcenso_city_fk` INT(11) DEFAULT NULL,
  	`users_fk` int(11) DEFAULT NULL,
  	PRIMARY KEY `id` (`id`),
  	KEY `edcenso_nation_fk` (`edcenso_nation_fk`),
  	KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  	KEY `edcenso_city_fk` (`edcenso_city_fk`),
  	KEY `users_fk` (`users_fk`),
  	CONSTRAINT `manager_identification_ibfk_1` FOREIGN KEY (`edcenso_nation_fk`) REFERENCES `edcenso_nation` (`id`),
  	CONSTRAINT `manager_identification_ibfk_2` FOREIGN KEY (`edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  	CONSTRAINT `manager_identification_ibfk_3` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`),
  	CONSTRAINT `manager_identification_ibfk_4` FOREIGN KEY (`users_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	CONSTRAINT `manager_identification_ibfk_5` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE `school_identification` ADD `number_ato` VARCHAR(100) NOT NULL AFTER `final_date`;

INSERT INTO `manager_identification` (
	`school_inep_id_fk`,
	`name`,
	`email`,
	`role`,
	`access_criterion`,
	`contract_type`,
	`cpf`,
	`number_ato`,
	`edcenso_uf_fk`,
	`edcenso_city_fk`
)
SELECT
s.inep_id as `school_inep_id_fk`,
s.manager_name as `name`,
s.manager_email as `email`,
s.manager_role as `role`,
s.manager_access_criterion as `access_criterion`,
s.manager_contract_type as `contract_type`,
s.manager_cpf as `cpf`,
s.number_ato `number_ato`,
s.edcenso_uf_fk as `edcenso_uf_fk`,
s.edcenso_city_fk as `edcenso_city_fk`
FROM `school_identification` s
WHERE s.manager_name IS NOT NULL;