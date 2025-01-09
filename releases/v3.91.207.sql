
-- app/migrations/2024-11-10_classroom_qtd_students_9º_ano/sql

CREATE OR REPLACE VIEW `classroom_qtd_students` AS
select
`c`.`school_inep_fk` AS `school_inep_fk`,
`c`.`inep_id` AS `inep_id`,
`c`.`id` AS `id`,
`c`.`name` AS `name`,
concat_ws(' - ',concat_ws(':',`c`.`initial_hour`,`c`.`initial_minute`),
concat_ws(':',`c`.`final_hour`,`c`.`final_minute`)) AS `time`,

`c`.`schooling` AS `schooling`,
`c`.`complementary_activity` AS `complementary_activity`,
`c`.`aee` AS `aee`,

case `c`.`modality` when 1 then 'Ensino Regular' when 2 then 'Educação Especial' when 3 then 'Educação de Jovens e Adultos (EJA)' when 4 then 'Educação Profissional' when 5 then 'Atendimento Educacional Especializado (AEE)' else 'Não se Aplica' end AS `modality`,

`esm`.`name` AS `stage`,count(`c`.`id`) AS `students`,`c`.`school_year` AS `school_year`,`se`.`status` AS `status` from ((`classroom` `c` join `student_enrollment` `se` on(`c`.`id` = `se`.`classroom_fk`)) left join `edcenso_stage_vs_modality` `esm` on(`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)) where `se`.`status` in (1,6,7,8,9,10) or `se`.`status` is null group by `c`.`id`;


update edcenso_stage_vs_modality set stage = 3 where upper(name) like '%9º ANO' or upper(alias) = '9º ANO';


-- app/migrations/2024-11-20_student_enrollment_history/sql

CREATE TABLE `student_enrollment_history` (
  `id` int NOT NULL,
  `student_enrollment_fk` int(11) NOT NULL,
  `status` int NOT NULL,
  `date` date NULL,
  `created_at` datetime NULL,
  FOREIGN KEY (`student_enrollment_fk`) REFERENCES `student_enrollment` (`id`)
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `student_enrollment_history`
CHANGE `date` `transfer_date` date NULL AFTER `status`,
ADD `class_transfer_date` date NULL AFTER `transfer_date`,
ADD `school_readmission_date` date NULL AFTER `class_transfer_date`;

ALTER TABLE `student_enrollment_history`
ADD `enrollment_date` date NULL AFTER `status`;

ALTER TABLE `student_enrollment_history`
DROP `created_at`;

ALTER TABLE `student_enrollment_history`
CHANGE `id` `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE `student_enrollment`
ADD `current_enrollment` tinyint NULL;

ALTER TABLE `student_enrollment_history`
ADD `created_at` datetime NULL;


-- app/migrations/2024-18-11_final_recovery_avarage_formula/2024-18-11_final_recovery_avarage_formula.sql


ALTER TABLE grade_unity add final_recovery_avarage_formula VARCHAR(25) NULL;
