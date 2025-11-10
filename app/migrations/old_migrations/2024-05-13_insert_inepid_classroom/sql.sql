create or replace
algorithm = UNDEFINED view `classroom_qtd_students` as
select
    `c`.`school_inep_fk` as `school_inep_fk`,
    `c`.`inep_id` as `inep_id`,
    `c`.`id` as `id`,
    `c`.`name` as `name`,
    concat_ws(' - ', concat_ws(':', `c`.`initial_hour`, `c`.`initial_minute`), concat_ws(':', `c`.`final_hour`, `c`.`final_minute`)) as `time`,
    (case
        `c`.`assistance_type` when 0 then 'NÃO SE APLICA'
        when 1 then 'CLASSE HOSPITALAR'
        when 2 then 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
        when 3 then 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR'
        else 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)'
    end) as `assistance_type`,
    (case
        `c`.`modality` when 1 then 'REGULAR'
        when 2 then 'EDUCAÇÃO ESPECIAL'
        when 3 then 'EJA'
        when 4 then 'NÃO SE APLICA'
        else 'ATENDIMENTO EDUCACIONAL ESPECIALIZADO (AEE)'
    end) as `modality`,
    `esm`.`name` as `stage`,
    count(`c`.`id`) as `students`,
    `c`.`school_year` as `school_year`,
    `se`.`status` as `status`
from ((`classroom` `c`join `student_enrollment` `se` on ((`c`.`id` = `se`.`classroom_fk`)))
left join `edcenso_stage_vs_modality` `esm` on
    ((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)))
where
    ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
group by
    `c`.`id`;
