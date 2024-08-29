-- classroom_instructors source

create or replace
algorithm = UNDEFINED view `classroom_instructors` as
-- classroom_instructors source
select
    `c`.`id` as `id`,
    `c`.`name` as `name`,
    `c`.`school_inep_fk` as `school_inep_fk`,
    concat_ws(
        ' - ',
        concat_ws(':', `c`.`initial_hour`, `c`.`initial_minute`),
        concat_ws(':', `c`.`final_hour`, `c`.`final_minute`)
    ) as `time`,
    (
        case
            `c`.`assistance_type`
            when 0 then 'NÃO SE APLICA'
            when 1 then 'CLASSE HOSPITALAR'
            when 2 then 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
            when 3 then 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR'
            else 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)'
        end
    ) as `assistance_type`,
    (
        case
            `c`.`modality`
            when 1 then 'REGULAR'
            when 2 then 'ESPECIAL'
            else 'EJA'
        end
    ) as `modality`,
    `esm`.`name` as `stage`,
    concat_ws(
        ' - ',
        if((`c`.`week_days_sunday` = 1), 'DOMINGO', null),
        if((`c`.`week_days_monday` = 1), 'SEGUNDA', null),
        if((`c`.`week_days_tuesday` = 1), 'TERÇA', null),
        if((`c`.`week_days_wednesday` = 1), 'QUARTA', null),
        if((`c`.`week_days_thursday` = 1), 'QUINTA', null),
        if((`c`.`week_days_friday` = 1), 'SEXTA', null),
        if((`c`.`week_days_saturday` = 1), 'SÁBADO', null)
    ) as `week_days`,
    `inf`.`id` as `instructor_id`,
    `inf`.`birthday_date` as `birthday_date`,
    `inf`.`name` as `instructor_name`,
    (
        case
            `ivd`.`scholarity`
            when 1 then 'Fundamental Incompleto'
            when 2 then 'Fundamental Completo'
            when 3 then 'Ensino Médio Normal/Magistério'
            when 4 then 'Ensino Médio Normal/Magistério Indígena'
            when 5 then 'Ensino Médio'
            else 'Superior'
        end
    ) as `scholarity`,
    GROUP_CONCAT(
        distinct ed.name 
        separator
        '<br>'
    ) as `disciplines`,
    `c`.`school_year` as `school_year`
from
    `classroom` `c`
    join `instructor_teaching_data` `itd` on (`itd`.`classroom_id_fk` = `c`.`id`)
    join `instructor_identification` `inf` on (`inf`.`id` = `itd`.`instructor_fk`)
    join teaching_matrixes tm on tm.teaching_data_fk = `itd`.id
    join curricular_matrix cm on tm.curricular_matrix_fk = cm.id
    join edcenso_discipline ed on ed.id = cm.discipline_fk 
    left join `instructor_variable_data` `ivd` on (`inf`.`id` = `ivd`.`id`)
    left join `edcenso_stage_vs_modality` `esm` on (`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)
-- where c.id = 2029
group by c.id, inf.id
order by
    `c`.`id`, ed.name;
    
