-- classroom_enrollment source
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