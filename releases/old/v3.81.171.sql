ALTER TABLE `schedule`
CHANGE `turn` `turn` varchar(45) NULL AFTER `schedule`;

update schedule set turn = 'M' where turn = 0;
update schedule set turn = 'T' where turn = 1;
update schedule set turn = 'N' where turn = 2;


-- ====================================================================================


CREATE TABLE grade_partial_recovery_weights (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    weight INT NOT NULL,
    unity_fk INT,
    partial_recovery_fk INT NOT NULL,
    FOREIGN KEY (unity_fk) REFERENCES grade_unity(id),
    FOREIGN KEY (partial_recovery_fk) REFERENCES grade_partial_recovery(id)
);

-- ====================================================================================


ALTER TABLE grade_results
    CHANGE COLUMN rec_bim_1 rec_partial_1 float NULL,
    CHANGE COLUMN rec_bim_2 rec_partial_2 float NULL,
    CHANGE COLUMN rec_bim_3 rec_partial_3 float NULL,
    CHANGE COLUMN rec_bim_4 rec_partial_4 float NULL,
    CHANGE COLUMN rec_bim_5 rec_partial_5 float NULL,
    CHANGE COLUMN rec_bim_6 rec_partial_6 float NULL,
    CHANGE COLUMN rec_bim_7 rec_partial_7 float NULL,
    CHANGE COLUMN rec_bim_8 rec_partial_8 float NULL,
    CHANGE COLUMN rec_sem_1 rec_partial_9 float NULL,
    CHANGE COLUMN rec_sem_2 rec_partial_10 float NULL,
    CHANGE COLUMN rec_sem_3 rec_partial_11 float NULL,
    CHANGE COLUMN rec_sem_4 rec_partial_12 float NULL;



-- =========================================================================================

create table `grade_partial_recovery` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `order_partial_recovery`INT NOT NULL,
    `grade_rules_fk` INT NOT NULL,
    `grade_calculation_fk` INT NOT NULL,
    CONSTRAINT pk_grade_partial_recovery PRIMARY KEY (id),
    CONSTRAINT fk_grade_partial_recovery_rules FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id),
    CONSTRAINT fk_grade_partial_recovery_calculation FOREIGN KEY (grade_calculation_fk) REFERENCES grade_calculation(id)
);



ALTER TABLE `grade_rules` ADD has_partial_recovery TINYINT DEFAULT 0 NOT NULL;

ALTER TABLE grade_unity ADD COLUMN parcial_recovery_fk INT NULL;
ALTER TABLE grade_unity
ADD CONSTRAINT fk_grade_unity_parcial_recovery
    FOREIGN KEY (parcial_recovery_fk)
    REFERENCES grade_partial_recovery(id)
    ON DELETE SET NULL;


-- =========================================================================================


ALTER TABLE grade
MODIFY COLUMN grade_unity_modality_fk INT NULL;

ALTER TABLE grade
ADD COLUMN grade_partial_recovery_fk INT NULL;

ALTER TABLE grade
ADD CONSTRAINT fk_grade_partial_recovery
FOREIGN KEY (grade_partial_recovery_fk) REFERENCES grade_partial_recovery(id);


-- ========================================================================================

DROP VIEW IF EXISTS `classroom_enrollment`;
CREATE OR REPLACE
ALGORITHM = UNDEFINED VIEW `classroom_enrollment` AS
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
    `se`.`daily_order` AS `daily_order`,
    `en`.`acronym` AS `nation`,
    `en`.`name` AS `nationality`,
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
