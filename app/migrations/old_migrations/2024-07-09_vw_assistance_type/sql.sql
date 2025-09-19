CREATE OR REPLACE
ALGORITHM = UNDEFINED VIEW `classroom_qtd_students` AS
select
    `c`.`school_inep_fk` AS `school_inep_fk`,
    `c`.`inep_id` AS `inep_id`,
    `c`.`id` AS `id`,
    `c`.`name` AS `name`,
    concat_ws(' - ', concat_ws(':', `c`.`initial_hour`, `c`.`initial_minute`), concat_ws(':', `c`.`final_hour`, `c`.`final_minute`)) AS `time`,
    (case
        `c`.`assistance_type` when 0 then 'NÃO SE APLICA'
        when 1 then 'CLASSE HOSPITALAR'
        when 2 then 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
        when 3 then 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR'
        when 4 then 'ATIVIDADE COMPLEMENTAR'
        else 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)'
    end) AS `assistance_type`,
    (case
        `c`.`modality` when 1 then 'REGULAR'
        when 2 then 'EDUCAÇÃO ESPECIAL'
        when 3 then 'EJA'
        when 4 then 'NÃO SE APLICA'
        else 'ATENDIMENTO EDUCACIONAL ESPECIALIZADO (AEE)'
    end) AS `modality`,
    `esm`.`name` AS `stage`,
    count(`c`.`id`) AS `students`,
    `c`.`school_year` AS `school_year`,
    `se`.`status` AS `status`
from
    ((`db_japaratuba`.`classroom` `c`
join `db_japaratuba`.`student_enrollment` `se` on
    ((`c`.`id` = `se`.`classroom_fk`)))
left join `db_japaratuba`.`edcenso_stage_vs_modality` `esm` on
    ((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)))
where
    ((`se`.`status` in (1, 6, 7, 8, 9, 10))
        or isnull(`se`.`status`))
group by
    `c`.`id`;