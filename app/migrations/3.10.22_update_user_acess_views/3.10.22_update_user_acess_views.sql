DROP VIEW IF EXISTS `ata_performance`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `ata_performance` AS
select
	`s`.`name` AS `school`,
	`se`.`status` AS `status`,
	concat(`ec`.`name`, ' - ', `eu`.`acronym`) AS `city`,
	date_format(now(), '%d') AS `day`,
	date_format(now(), '%m') AS `month`,
	date_format(now(), '%Y') AS `year`,
	substring_index(`svm`.`name`, ' - ', 1) AS `ensino`,
	`c`.`name` AS `name`,
	(case
		`c`.`turn` when 'M' then 'Matutino'
		when 'T' then 'Vespertino'
		else 'Noturno'
	end) AS `turn`,
	substring_index(`svm`.`name`, ' - ',-(1)) AS `serie`,
	`c`.`school_year` AS `school_year`,
	`c`.`id` AS `classroom_id`,
	concat_ws('|', if((`c`.`discipline_biology` = 1), 'Biologia', NULL), if((`c`.`discipline_science` = 1), 'Ciência', NULL), if((`c`.`discipline_physical_education` = 1), 'Educação Física', NULL), if((`c`.`discipline_religious` = 1), 'Ensino Religioso', NULL), if((`c`.`discipline_philosophy` = 1), 'Filosofia', NULL), if((`c`.`discipline_physics` = 1), 'Física', NULL), if((`c`.`discipline_geography` = 1), 'Geografia', NULL), if((`c`.`discipline_history` = 1), 'História', NULL), if((`c`.`discipline_native_language` = 1), 'Lingua Nativa', NULL), if((`c`.`discipline_mathematics` = 1), 'Matemática', NULL), if((`c`.`discipline_pedagogical` = 1), 'Pedagogia', NULL), if((`c`.`discipline_language_portuguese_literature` = 1), 'Português', NULL), if((`c`.`discipline_chemistry` = 1), 'Química', NULL), if((`c`.`discipline_arts` = 1), 'Ártes', NULL), if((`c`.`discipline_professional_disciplines` = 1), 'Disciplina Proficionalizante', NULL), if((`c`.`discipline_foreign_language_spanish` = 1), 'Espanhol', NULL), if((`c`.`discipline_social_study` = 1), 'Estudo Social', NULL), if((`c`.`discipline_foreign_language_franch` = 1), 'Francês', NULL), if((`c`.`discipline_foreign_language_english` = 1), 'Inglês', NULL), if((`c`.`discipline_informatics` = 1), 'Informática', NULL), if((`c`.`discipline_libras` = 1), 'Libras', NULL), if((`c`.`discipline_foreign_language_other` = 1), 'Outro Idioma', NULL), if((`c`.`discipline_sociocultural_diversity` = 1), 'Sociedade e Cultura', NULL), if((`c`.`discipline_others` = 1), 'Outras', NULL)) AS `disciplines`
from
	(((((`classroom` `c`
join `school_identification` `s` on
	((`c`.`school_inep_fk` = `s`.`inep_id`)))
join `student_enrollment` `se` on
	((`s`.`inep_id` = `se`.`school_inep_id_fk`)))
left join `edcenso_city` `ec` on
	((`s`.`edcenso_city_fk` = `ec`.`id`)))
left join `edcenso_uf` `eu` on
	((`s`.`edcenso_uf_fk` = `eu`.`id`)))
left join `edcenso_stage_vs_modality` `svm` on
	((`c`.`edcenso_stage_vs_modality_fk` = `svm`.`id`)));

DROP VIEW IF EXISTS `classroom_enrollment`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `classroom_enrollment` AS
select
	`s`.`id` AS `enrollment`,
	`s`.`name` AS `name`,
	if((`s`.`sex` = 1),
	'M',
	'F') AS `sex`,
	`s`.`birthday` AS `birthday`,
	`se`.`current_stage_situation` AS `situation`,
	`se`.`admission_type` AS `admission_type`,
	`se`.`status` AS `status`,
	`en`.`acronym` AS `nation`,
	`ec`.`name` AS `city`,
	`euf`.`acronym` AS `uf`,
	`sd`.`address` AS `address`,
	`sd`.`number` AS `number`,
	`sd`.`complement` AS `complement`,
	`sd`.`neighborhood` AS `neighborhood`,
	`sd`.`civil_certification` AS `cc`,
	`sd`.`civil_register_enrollment_number` AS `cc_new`,
	`sd`.`civil_certification_term_number` AS `cc_number`,
	`sd`.`civil_certification_book` AS `cc_book`,
	`sd`.`civil_certification_sheet` AS `cc_sheet`,
	concat(`s`.`filiation_1`, '<br>', `s`.`filiation_2`) AS `parents`,
	`s`.`deficiency` AS `deficiency`,
	`c`.`id` AS `classroom_id`,
	`c`.`school_year` AS `year`
from
	((((((`student_identification` `s`
join `student_documents_and_address` `sd` on
	((`s`.`id` = `sd`.`id`)))
left join `edcenso_nation` `en` on
	((`s`.`edcenso_nation_fk` = `en`.`id`)))
left join `edcenso_uf` `euf` on
	((`s`.`edcenso_uf_fk` = `euf`.`id`)))
left join `edcenso_city` `ec` on
	((`s`.`edcenso_city_fk` = `ec`.`id`)))
join `student_enrollment` `se` on
	((`s`.`id` = `se`.`student_fk`)))
join `classroom` `c` on
	((`se`.`classroom_fk` = `c`.`id`)));

DROP VIEW IF EXISTS `classroom_instructors`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `classroom_instructors` AS
select
	`c`.`id` AS `id`,
	`c`.`name` AS `name`,
	`c`.`school_inep_fk` AS `school_inep_fk`,
	concat_ws(' - ', concat_ws(':', `c`.`initial_hour`, `c`.`initial_minute`), concat_ws(':', `c`.`final_hour`, `c`.`final_minute`)) AS `time`,
	(case
		`c`.`assistance_type` when 0 then 'NÃO SE APLICA'
		when 1 then 'CLASSE HOSPITALAR'
		when 2 then 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
		when 3 then 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR'
		else 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)'
	end) AS `assistance_type`,
	(case
		`c`.`modality` when 1 then 'REGULAR'
		when 2 then 'ESPECIAL'
		else 'EJA'
	end) AS `modality`,
	`esm`.`name` AS `stage`,
	concat_ws(' - ', if((`c`.`week_days_sunday` = 1), 'DOMINGO', NULL), if((`c`.`week_days_monday` = 1), 'SEGUNDA', NULL), if((`c`.`week_days_tuesday` = 1), 'TERÇA', NULL), if((`c`.`week_days_wednesday` = 1), 'QUARTA', NULL), if((`c`.`week_days_thursday` = 1), 'QUINTA', NULL), if((`c`.`week_days_friday` = 1), 'SEXTA', NULL), if((`c`.`week_days_saturday` = 1), 'SÁBADO', NULL)) AS `week_days`,
	`inf`.`id` AS `instructor_id`,
	`inf`.`birthday_date` AS `birthday_date`,
	`inf`.`name` AS `instructor_name`,
	(case
		`ivd`.`scholarity` when 1 then 'Fundamental Incompleto'
		when 2 then 'Fundamental Completo'
		when 3 then 'Ensino Médio Normal/Magistério'
		when 4 then 'Ensino Médio Normal/Magistério Indígena'
		when 5 then 'Ensino Médio'
		else 'Superior'
	end) AS `scholarity`,
	concat_ws('<br>', `d1`.`name`, `d2`.`name`, `d3`.`name`, `d4`.`name`, `d5`.`name`, `d6`.`name`, `d7`.`name`, `d8`.`name`, `d9`.`name`, `d10`.`name`, `d11`.`name`, `d12`.`name`, `d13`.`name`) AS `disciplines`,
	`c`.`school_year` AS `school_year`
from
	(((((((((((((((((`classroom` `c`
join `instructor_teaching_data` `itd` on
	((`itd`.`classroom_id_fk` = `c`.`id`)))
join `instructor_identification` `inf` on
	((`inf`.`id` = `itd`.`instructor_fk`)))
left join `instructor_variable_data` `ivd` on
	((`inf`.`id` = `ivd`.`id`)))
left join `edcenso_stage_vs_modality` `esm` on
	((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)))
left join `edcenso_discipline` `d1` on
	((`d1`.`id` = `itd`.`discipline_1_fk`)))
left join `edcenso_discipline` `d2` on
	((`d2`.`id` = `itd`.`discipline_2_fk`)))
left join `edcenso_discipline` `d3` on
	((`d3`.`id` = `itd`.`discipline_3_fk`)))
left join `edcenso_discipline` `d4` on
	((`d4`.`id` = `itd`.`discipline_4_fk`)))
left join `edcenso_discipline` `d5` on
	((`d5`.`id` = `itd`.`discipline_5_fk`)))
left join `edcenso_discipline` `d6` on
	((`d6`.`id` = `itd`.`discipline_6_fk`)))
left join `edcenso_discipline` `d7` on
	((`d7`.`id` = `itd`.`discipline_7_fk`)))
left join `edcenso_discipline` `d8` on
	((`d8`.`id` = `itd`.`discipline_8_fk`)))
left join `edcenso_discipline` `d9` on
	((`d9`.`id` = `itd`.`discipline_9_fk`)))
left join `edcenso_discipline` `d10` on
	((`d10`.`id` = `itd`.`discipline_10_fk`)))
left join `edcenso_discipline` `d11` on
	((`d11`.`id` = `itd`.`discipline_11_fk`)))
left join `edcenso_discipline` `d12` on
	((`d12`.`id` = `itd`.`discipline_12_fk`)))
left join `edcenso_discipline` `d13` on
	((`d13`.`id` = `itd`.`discipline_13_fk`)))
order by
	`c`.`id`;

DROP VIEW IF EXISTS `classroom_qtd_students`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `classroom_qtd_students` AS
select
	`c`.`school_inep_fk` AS `school_inep_fk`,
	`c`.`id` AS `id`,
	`c`.`name` AS `name`,
	concat_ws(' - ', concat_ws(':', `c`.`initial_hour`, `c`.`initial_minute`), concat_ws(':', `c`.`final_hour`, `c`.`final_minute`)) AS `time`,
	(case
		`c`.`assistance_type` when 0 then 'NÃO SE APLICA'
		when 1 then 'CLASSE HOSPITALAR'
		when 2 then 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
		when 3 then 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR'
		else 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)'
	end) AS `assistance_type`,
	(case
		`c`.`modality` when 1 then 'REGULAR'
		when 2 then 'ESPECIAL'
		else 'EJA'
	end) AS `modality`,
	`esm`.`name` AS `stage`,
	count(`c`.`id`) AS `students`,
	`c`.`school_year` AS `school_year`,
	`se`.`status` AS `status`
from
	((`classroom` `c`
join `student_enrollment` `se` on
	((`c`.`id` = `se`.`classroom_fk`)))
left join `edcenso_stage_vs_modality` `esm` on
	((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)))
where
	((`se`.`status` = 1)
		or isnull(`se`.`status`))
group by
	`c`.`id`;

DROP VIEW IF EXISTS `imob_classroom`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `imob_classroom` AS
select
	count(distinct `c`.`id`) AS `c_total`,
	`c`.`school_year` AS `year`,
	`c`.`school_inep_fk` AS `school_inep_fk`
from
	`classroom` `c`
group by
	`c`.`school_year`,
	`c`.`school_inep_fk`
order by
	`c`.`school_year`;

DROP VIEW IF EXISTS `imob_student_enrollment`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `imob_student_enrollment` AS
select
	count(distinct `se`.`id`) AS `se_total`,
	sum((case when ((month(`se`.`create_date`) >= 1) and (month(`se`.`create_date`) < 6)) then 1 else 0 end)) AS `se_half1`,
	sum((case when ((month(`se`.`create_date`) >= 6) and (month(`se`.`create_date`) < 12)) then 1 else 0 end)) AS `se_half2`,
	`c`.`school_year` AS `year`
from
	(`student_enrollment` `se`
join `classroom` `c` on
	((`se`.`classroom_fk` = `c`.`id`)))
group by
	`c`.`school_year`
order by
	`c`.`school_year`;

DROP VIEW IF EXISTS `studentsdeclaration`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `studentsdeclaration` AS
select
	`s`.`name` AS `name`,
	`s`.`birthday` AS `birthday`,
	`ec`.`name` AS `birth_city`,
	`eu`.`acronym` AS `birth_uf`,
	`sd`.`civil_certification` AS `cc`,
	`sd`.`civil_register_enrollment_number` AS `cc_new`,
	`sd`.`civil_certification_term_number` AS `cc_number`,
	`sd`.`civil_certification_book` AS `cc_book`,
	`sd`.`civil_certification_sheet` AS `cc_sheet`,
	`ecn`.`name` AS `cc_city`,
	`eun`.`acronym` AS `cc_uf`,
	`s`.`filiation_1` AS `mother`,
	`s`.`filiation_2` AS `father`,
	`c`.`school_year` AS `year`,
	`c`.`name` AS `classroom`,
	`c`.`turn` AS `turn`,
	`edsm`.`name` AS `stage`,
	`c`.`id` AS `classroom_id`,
	`s`.`id` AS `student_id`,
	`se`.`id` AS `enrollment_id`
from
	(((((((((`student_identification` `s`
left join `edcenso_uf` `eu` on
	((`s`.`edcenso_uf_fk` = `eu`.`id`)))
left join `edcenso_city` `ec` on
	((`s`.`edcenso_city_fk` = `ec`.`id`)))
left join `edcenso_nation` `en` on
	((`s`.`edcenso_nation_fk` = `en`.`id`)))
join `student_documents_and_address` `sd` on
	((`s`.`id` = `sd`.`id`)))
left join `edcenso_uf` `eun` on
	((`sd`.`notary_office_uf_fk` = `eun`.`id`)))
left join `edcenso_city` `ecn` on
	((`sd`.`notary_office_city_fk` = `ecn`.`id`)))
join `student_enrollment` `se` on
	((`s`.`id` = `se`.`student_fk`)))
join `classroom` `c` on
	(((`se`.`classroom_fk` = `c`.`id`)
		and (`c`.`assistance_type` <> 4))))
left join `edcenso_stage_vs_modality` `edsm` on
	((((`se`.`edcenso_stage_vs_modality_fk` is not null)
		and (`edsm`.`id` = `se`.`edcenso_stage_vs_modality_fk`))
		or (`edsm`.`id` = `c`.`edcenso_stage_vs_modality_fk`))))
order by
	`s`.`name`;

DROP VIEW IF EXISTS `studentsfile`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `studentsfile` AS
select
	`s`.`id` AS `id`,
	`se`.`id` AS `enrollment_id`,
	(case
		`svm`.`stage` when '1' then 'EDUCAÇÃO INFANTIL'
		when '2' then 'ENSINO FUNDAMENTAL'
		when '3' then 'ENSINO FUNDAMENTAL'
		when '4' then 'ENSINO MÉDIO'
		when '5' then 'EDUCAÇÃO PROFISSIONAL'
		when '6' then 'EDUCAÇÃO DE JOVENS E ADULTOS'
		when '7' then (case
			`svm`.`id` when '56' then 'MULTIETAPA'
			else 'ENSINO FUNDAMENTAL'
		end)
	end) AS `stage`,
	concat((case when (`svm`.`id` in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38)) then 'NA ' when (`svm`.`id` in (14, 15, 16, 17, 18, 19, 20, 21, 41)) then 'NO ' else '' end),(case when (`svm`.`id` = 1) then 'CRECHE' when (`svm`.`id` = 2) then 'PRÉ-ESCOLA' when (`svm`.`id` = 3) then 'EDUCAÇÃO INFANTIL' when (`svm`.`id` in (4, 14, 25, 30, 35)) then '1' when (`svm`.`id` in (5, 15, 26, 31, 36)) then '2' when (`svm`.`id` in (6, 16, 27, 32, 37)) then '3' when (`svm`.`id` in (7, 17, 28, 33, 38)) then '4' when (`svm`.`id` in (8, 18)) then '5' when (`svm`.`id` in (9, 19)) then '6' when (`svm`.`id` in (10, 20)) then '7' when (`svm`.`id` in (11, 21)) then '8' when (`svm`.`id` = 41) then '9' else '' end),(case when (`svm`.`id` in (1, 2, 3)) then '' when (`svm`.`id` in (4, 5, 6, 7, 8, 9, 10, 11, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38)) then 'ª SÉRIE' when (`svm`.`id` in (14, 15, 16, 17, 18, 19, 20, 21, 41)) then 'º ANO' else 'NA ____________________' end)) AS `class`,
	`s`.`inep_id` AS `inep_id`,
	`sd`.`nis` AS `nis`,
	`s`.`name` AS `name`,
	`ec`.`name` AS `birth_city`,
	if((`s`.`sex` = 1),
	'Masculino',
	'Feminino') AS `gender`,
	(case
		`s`.`color_race` when '1' then 'Branca'
		when '2' then 'Preta'
		when '3' then 'Parda'
		when '4' then 'Amarela'
		when '5' then 'Indígena'
		else 'Não Declarada'
	end) AS `color`,
	`s`.`birthday` AS `birthday`,
	`eu`.`acronym` AS `birth_uf`,
	`en`.`name` AS `nation`,
	`sd`.`address` AS `address`,
	`eca`.`name` AS `adddress_city`,
	`eua`.`acronym` AS `address_uf`,
	`sd`.`number` AS `number`,
	`sd`.`cep` AS `cep`,
	`sd`.`rg_number` AS `rg`,
	`sd`.`cpf` AS `cpf`,
	`sd`.`civil_certification` AS `cc`,
	if((`sd`.`civil_certification_type` = 2),
	'Casamento',
	'Nascimento') AS `cc_type`,
	`eno`.`name` AS `cc_name`,
	`sd`.`civil_register_enrollment_number` AS `cc_new`,
	`sd`.`civil_certification_term_number` AS `cc_number`,
	`sd`.`civil_certification_book` AS `cc_book`,
	`sd`.`civil_certification_sheet` AS `cc_sheet`,
	`ecn`.`name` AS `cc_city`,
	`eun`.`acronym` AS `cc_uf`,
	`s`.`filiation_1` AS `mother`,
	`s`.`filiation_1_rg` AS `mother_rg`,
	`s`.`filiation_1_cpf` AS `mother_cpf`,
	`s`.`filiation_1_job` AS `mother_job`,
	`s`.`filiation_2` AS `father`,
	`s`.`filiation_2_rg` AS `father_rg`,
	`s`.`filiation_2_cpf` AS `father_cpf`,
	`s`.`filiation_2_job` AS `father_job`,
	`s`.`responsable` AS `responsable`,
	(case
		`s`.`responsable` when '0' then concat(`s`.`filiation_2`, ' (PAI)')
		when '1' then concat(`s`.`filiation_1`, ' (MÃE)')
		when '2' then `s`.`responsable_name`
	end) AS `responsable_name`,
	`s`.`responsable_rg` AS `responsable_rg`,
	`s`.`responsable_cpf` AS `responsable_cpf`,
	(case
		`s`.`responsable_scholarity` when '0' then 'Não sabe Ler e Escrever'
		when '1' then 'Sabe Ler e Escrever'
		when '2' then 'Ensino Fundamental Incompleto'
		when '3' then 'Ensino Fundamental Completo'
		when '4' then 'Ensino Médio Incompleto'
		when '5' then 'Ensino Médio Completo'
		when '6' then 'Ensino Superior Incompleto'
		when '7' then 'Ensino Superior Completo'
	end) AS `responsable_scholarity`,
	(case
		`s`.`filiation_1_scholarity` when '0' then 'Não sabe Ler e Escrever'
		when '1' then 'Sabe Ler e Escrever'
		when '2' then 'Ensino Fundamental Incompleto'
		when '3' then 'Ensino Fundamental Completo'
		when '4' then 'Ensino Médio Incompleto'
		when '5' then 'Ensino Médio Completo'
		when '6' then 'Ensino Superior Incompleto'
		when '7' then 'Ensino Superior Completo'
	end) AS `mother_scholarity`,
	(case
		`s`.`filiation_2_scholarity` when '0' then 'Não sabe Ler e Escrever'
		when '1' then 'Sabe Ler e Escrever'
		when '2' then 'Ensino Fundamental Incompleto'
		when '3' then 'Ensino Fundamental Completo'
		when '4' then 'Ensino Médio Incompleto'
		when '5' then 'Ensino Médio Completo'
		when '6' then 'Ensino Superior Incompleto'
		when '7' then 'Ensino Superior Completo'
	end) AS `father_scholarity`,
	`s`.`responsable_job` AS `responsable_job`,
	concat_ws('; ', if((`sd`.`received_cc` = 1), 'Certidão de nascimento', NULL), if((`sd`.`received_address` = 1), 'Comprovante de endereço', NULL), if((`sd`.`received_photo` = 1), 'Foto 3x4', NULL), if((`sd`.`received_nis` = 1), 'Comprovante NIS', NULL), if((`sd`.`received_history` = 1), 'Histórico', NULL), if((`sd`.`received_responsable_rg` = 1), 'Cópia RG (responsável)', NULL), if((`sd`.`received_responsable_cpf` = 1), 'Cópia CPF (responsável)', NULL)) AS `received_documents`,
	if(isnull(`se`.`school_admission_date`),
	NULL,
	`se`.`school_admission_date`) AS `school_admission_date`,
	(case
		`se`.`current_stage_situation` when 0 then 'Primeira matrícula no curso (nível e/ou modalidade de ensino)'
		when 1 then 'Promovido na série anterior do mesmo curso (nível e/ou modalidade de ensino)'
		when 2 then 'Repetente'
	end) AS `current_stage_situation`,
	(case
		`se`.`previous_stage_situation` when 0 then 'Não frequentou'
		when 1 then 'Reprovado'
		when 2 then 'Afastado por transferência'
		when 3 then 'Afastado por abandono'
		when 4 then 'Matrícula final em Educação Infantil'
	end) AS `previous_stage_situation`,
	if((`s`.`bf_participator` = 0),
	'Não',
	'Sim') AS `bf_participator`,
	`s`.`food_restrictions` AS `food_restrictions`,
	`se`.`transport_responsable_government` AS `transport_responsable_government`,
	`se`.`vehicle_type_van` AS `vehicle_type_van`,
	`se`.`vehicle_type_microbus` AS `vehicle_type_microbus`,
	`se`.`vehicle_type_bus` AS `vehicle_type_bus`,
	`se`.`vehicle_type_bike` AS `vehicle_type_bike`,
	`se`.`vehicle_type_animal_vehicle` AS `vehicle_type_animal_vehicle`,
	`se`.`vehicle_type_other_vehicle` AS `vehicle_type_other_vehicle`,
	`se`.`vehicle_type_waterway_boat_5` AS `vehicle_type_waterway_boat_5`,
	`se`.`vehicle_type_waterway_boat_5_15` AS `vehicle_type_waterway_boat_5_15`,
	`se`.`vehicle_type_waterway_boat_15_35` AS `vehicle_type_waterway_boat_15_35`,
	`se`.`vehicle_type_waterway_boat_35` AS `vehicle_type_waterway_boat_35`,
	`se`.`vehicle_type_metro_or_train` AS `vehicle_type_metro_or_train`,
	`se`.`status` AS `status`,
	if((isnull(`se`.`vehicle_type_bus`)
		or (`se`.`vehicle_type_bus` = 0)),
	'Não',
	concat_ws(': ', 'Sim', concat_ws('; ', if((`se`.`vehicle_type_van` = 1), 'Van / Kombi', if(isnull(`se`.`vehicle_type_van`), 'Van / Kombi', NULL)), if((`se`.`vehicle_type_microbus` = 1), 'Microônibus', if(isnull(`se`.`vehicle_type_microbus`), 'Microônibus', NULL)), if((`se`.`vehicle_type_bus` = 1), 'Ônibus', if(isnull(`se`.`vehicle_type_bus`), 'Ônibus', NULL)), if((`se`.`vehicle_type_bike` = 1), 'Bicicleta', if(isnull(`se`.`vehicle_type_bike`), 'Bicicleta', NULL)), if((`se`.`vehicle_type_animal_vehicle` = 1), 'Tração animal', if(isnull(`se`.`vehicle_type_animal_vehicle`), 'Tração animal', NULL)), if((`se`.`vehicle_type_other_vehicle` = 1), 'Rodoviário - Outro', if(isnull(`se`.`vehicle_type_other_vehicle`), 'Rodoviário - Outro', NULL)), if((`se`.`vehicle_type_waterway_boat_5` = 1), 'Embarcação - Até 5 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_5`), 'Embarcação - Até 5 alunos', NULL)), if((`se`.`vehicle_type_waterway_boat_5_15` = 1), 'Embarcação - De 5 a 15 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_5_15`), 'Embarcação - De 5 a 15 alunos', NULL)), if((`se`.`vehicle_type_waterway_boat_15_35` = 1), 'Embarcação - De 15 a 35 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_15_35`), 'Embarcação - De 15 a 35 alunos', NULL)), if((`se`.`vehicle_type_waterway_boat_35` = 1), 'Embarcação - Acima de 35 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_35`), 'Embarcação - Acima de 35 alunos', NULL)), if((`se`.`vehicle_type_metro_or_train` = 1), 'Trem / Metrô', if(isnull(`se`.`vehicle_type_metro_or_train`), 'Trem / Metrô', NULL))))) AS `public_transport`,
	`s`.`responsable_telephone` AS `responsable_telephone`,
	if((`s`.`deficiency` = 0),
	'Não',
	concat_ws(': ', 'Possui', concat_ws(', ', if((`s`.`deficiency_type_blindness` = 1), 'Cegueira', NULL), if((`s`.`deficiency_type_low_vision` = 1), 'Baixa visão', NULL), if((`s`.`deficiency_type_deafness` = 1), 'Surdez', NULL), if((`s`.`deficiency_type_disability_hearing` = 1), 'Deficiência Auditiva', NULL), if((`s`.`deficiency_type_deafblindness` = 1), 'Surdocegueira', NULL), if((`s`.`deficiency_type_phisical_disability` = 1), 'Deficiência Física', NULL), if((`s`.`deficiency_type_intelectual_disability` = 1), 'Deficiência Intelectual', NULL), if((`s`.`deficiency_type_multiple_disabilities` = 1), 'Deficiência Múltipla', NULL), if((`s`.`deficiency_type_autism` = 1), 'Autismo Infantil', NULL), if((`s`.`deficiency_type_aspenger_syndrome` = 1), 'Síndrome de Asperger', NULL), if((`s`.`deficiency_type_rett_syndrome` = 1), 'Síndrome de Rett', NULL), if((`s`.`deficiency_type_childhood_disintegrative_disorder` = 1), 'Transtorno Desintegrativo da Infância', NULL), if((`s`.`deficiency_type_gifted` = 1), 'Altas habilidades / Superdotação', NULL)))) AS `deficiency`,
	(case
		`sd`.`justice_restriction` when 1 then 'LA - Liberdade Assistida'
		when 2 then 'PSC - Prestação de Serviços Comunitários'
		else 'Não'
	end) AS `justice_restriction`
from
	((((((((((((`student_identification` `s`
join `student_documents_and_address` `sd` on
	((`s`.`id` = `sd`.`id`)))
join `student_enrollment` `se` on
	((`s`.`id` = `se`.`student_fk`)))
left join `classroom` `c` on
	((`se`.`classroom_fk` = `c`.`id`)))
left join `edcenso_stage_vs_modality` `svm` on
	((`c`.`edcenso_stage_vs_modality_fk` = `svm`.`id`)))
left join `edcenso_uf` `eu` on
	((`s`.`edcenso_uf_fk` = `eu`.`id`)))
left join `edcenso_city` `ec` on
	((`s`.`edcenso_city_fk` = `ec`.`id`)))
left join `edcenso_nation` `en` on
	((`s`.`edcenso_nation_fk` = `en`.`id`)))
left join `edcenso_uf` `eua` on
	((`sd`.`edcenso_uf_fk` = `eua`.`id`)))
left join `edcenso_city` `eca` on
	((`sd`.`edcenso_city_fk` = `eca`.`id`)))
left join `edcenso_uf` `eun` on
	((`sd`.`notary_office_uf_fk` = `eun`.`id`)))
left join `edcenso_city` `ecn` on
	((`sd`.`notary_office_city_fk` = `ecn`.`id`)))
left join `edcenso_notary_office` `eno` on
	((`sd`.`edcenso_notary_office_fk` = `eno`.`cod`)))
order by
	`s`.`name`;

DROP VIEW IF EXISTS `studentsfile2`;

CREATE ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `studentsfile2` AS
select
	`s`.`id` AS `id`,
	`s`.`name` AS `name`,
	`ec`.`name` AS `birth_city`,
	`s`.`birthday` AS `birthday`,
	`eu`.`acronym` AS `birth_uf`,
	`en`.`name` AS `nation`,
	`sd`.`address` AS `address`,
	`eca`.`name` AS `adddress_city`,
	`eua`.`acronym` AS `address_uf`,
	`sd`.`cep` AS `cep`,
	`sd`.`rg_number` AS `rg`,
	`sd`.`civil_certification` AS `cc`,
	`sd`.`civil_register_enrollment_number` AS `cc_new`,
	`sd`.`civil_certification_term_number` AS `cc_number`,
	`sd`.`civil_certification_book` AS `cc_book`,
	`sd`.`civil_certification_sheet` AS `cc_sheet`,
	`ecn`.`name` AS `cc_city`,
	`eun`.`acronym` AS `cc_uf`,
	`s`.`filiation_1` AS `mother`,
	`s`.`filiation_2` AS `father`
from
	((((((((`student_identification` `s`
left join `edcenso_uf` `eu` on
	((`s`.`edcenso_uf_fk` = `eu`.`id`)))
left join `edcenso_city` `ec` on
	((`s`.`edcenso_city_fk` = `ec`.`id`)))
left join `edcenso_nation` `en` on
	((`s`.`edcenso_nation_fk` = `en`.`id`)))
join `student_documents_and_address` `sd` on
	((`s`.`id` = `sd`.`id`)))
left join `edcenso_uf` `eua` on
	((`sd`.`edcenso_uf_fk` = `eua`.`id`)))
left join `edcenso_city` `eca` on
	((`sd`.`edcenso_city_fk` = `eca`.`id`)))
left join `edcenso_uf` `eun` on
	((`sd`.`notary_office_uf_fk` = `eun`.`id`)))
left join `edcenso_city` `ecn` on
	((`sd`.`notary_office_city_fk` = `ecn`.`id`)))
order by
	`s`.`name`;