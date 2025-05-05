CREATE TABLE `grade_unity_periods` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `initial_date` date NOT NULL,
  `grade_unity_fk` int NOT NULL,
  `school_year` int NOT NULL
);

ALTER TABLE `grade_unity_periods`
ADD FOREIGN KEY (`grade_unity_fk`) REFERENCES `grade_unity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- =============================================================================================================

ALTER TABLE `school_stages_concept_grades`
RENAME TO `school_stages`;

delete from school_stages;

insert into school_stages (school_fk, edcenso_stage_vs_modality_fk)
select distinct si.inep_id, c.edcenso_stage_vs_modality_fk from school_identification si join classroom c on si.inep_id = c.school_inep_fk where edcenso_stage_vs_modality_fk  <> 0;

-- ============================================================================================================


INSERT INTO food_meal_type (description)
VALUES
  ('Café da Manhã'),
  ('Almoço'),
  ('Jantar'),
  ('Lanche da Tarde'),
  ('Ceia');

INSERT INTO food_public_target (id, name)
VALUES
  (1,'Creche (7-11 meses)'),
  (2, 'Creche (1-3 anos)'),
  (3, 'Pré-escola (3-5)'),
  (4, 'Ensino Fundamental (6-10)'),
  (5, 'Ensino Fundamental (11-15)'),
  (6, 'Ensino Médio'),
  (7, 'EJA')

INSERT
INTO
food_measurement (unit,value, measure)
VALUES
  ('concha pequena', 80.00,'ml'),
  ('Colher de sopa',15,'ml'),
  ('Cálice', 45,'ml'),
  ('Caneca', 300,'ml'),
  ('Dedo', 50,'ml'),
  ('Dedo de copo',25,'ml'),
  ('Dedo de caneca',60,'ml'),
  ('Pitada',1.8,'ml');

INSERT INTO instance_config (parameter_key,parameter_name, value) VALUES
('FEAT_FOOD', 'novo módulo de merenda', 0)

-- ============================================================================================================

INSERT INTO instance_config (parameter_key, parameter_name, value)
VALUES ('FEAT_REPORTCARD', 'Lançamento de notas de buzios', 0);

INSERT INTO instance_config (parameter_key, parameter_name, value)
VALUES ('FEAT_GRADES', 'Notas', 1);

-- ============================================================================================================

CREATE OR REPLACE VIEW `studentsfile` AS
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
	end) AS `justice_restriction`,
	`sr`.`celiac` as `celiac`,
	`sr`.`diabetes` as `diabetes`,
	`sr`.`hypertension` as `hypertension`,
	`sr`.`iron_deficiency_anemia` as `iron_deficiency_anemia`,
	`sr`.`sickle_cell_anemia` as `sickle_cell_anemia`,
	`sr`.`lactose_intolerance` as `lactose_intolerance`,
	`sr`.`malnutrition` as `malnutrition`,
	`sr`.`obesity` as `obesity`,
	`sr`.`others` as `others`,
	`sd`.`consent_form` as `consent_form`
from
	(((((((((((((`student_identification` `s`
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
left join `student_restrictions` `sr` on
	((`sr`.`student_fk` = `s`.`id`)))
order by
	`s`.`name`;

-- ============================================================================================================

ALTER TABLE attendance MODIFY COLUMN `local` VARCHAR(50);

-- ============================================================================================================

ALTER TABLE grade_results ADD final_concept int NULL;

-- ============================================================================================================

ALTER TABLE lunch_meal MODIFY COLUMN restrictions varchar(1000);
ALTER TABLE edcenso_discipline MODIFY COLUMN name varchar(100);


