CREATE OR REPLACE 
VIEW `StudentsFileBoquim` AS
    select 
        `s`.`id` AS `id`,
        s.inep_id as inep_id,
        `s`.`name` AS `name`,
        `ec`.`name` AS `birth_city`,
        if((`s`.`sex` = 1),
            'Masculino',
            'Feminino') AS `gender`,
        (case `s`.`color_race`
            when '1' then 'Branca'
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
        `eca`.`name` AS `address_city`,
        `eua`.`acronym` AS `address_uf`,
        `sd`.`cep` AS `cep`,
        `sd`.`rg_number` AS `rg`,
        `sd`.`civil_certification` AS `cc`,
        `eno`.`name` AS `cc_name`,
        `sd`.`civil_register_enrollment_number` AS `cc_new`,
        `sd`.`civil_certification_term_number` AS `cc_number`,
        `sd`.`civil_certification_book` AS `cc_book`,
        `sd`.`civil_certification_sheet` AS `cc_sheet`,
        sd.cpf as cpf,
        sd.nis as nis,
        `ecn`.`name` AS `cc_city`,
        `eun`.`acronym` AS `cc_uf`,
        `s`.`mother_name` AS `mother`,
        `s`.`father_name` AS `father`
    from
        (((((((((`student_identification` `s`
        join `student_documents_and_address` `sd` ON ((`s`.`id` = `sd`.`id`)))
        left join `edcenso_uf` `eu` ON ((`s`.`edcenso_uf_fk` = `eu`.`id`)))
        left join `edcenso_city` `ec` ON ((`s`.`edcenso_city_fk` = `ec`.`id`)))
        left join `edcenso_nation` `en` ON ((`s`.`edcenso_nation_fk` = `en`.`id`)))
        left join `edcenso_uf` `eua` ON ((`sd`.`edcenso_uf_fk` = `eua`.`id`)))
        left join `edcenso_city` `eca` ON ((`sd`.`edcenso_city_fk` = `eca`.`id`)))
        left join `edcenso_uf` `eun` ON ((`sd`.`notary_office_uf_fk` = `eun`.`id`)))
        left join `edcenso_city` `ecn` ON ((`sd`.`notary_office_city_fk` = `ecn`.`id`)))
        left join `edcenso_notary_office` `eno` ON ((`sd`.`edcenso_notary_office_fk` = `eno`.`id`)))
    order by `s`.`name`;
