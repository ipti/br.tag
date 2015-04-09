CREATE 
     OR REPLACE ALGORITHM = UNDEFINED 
    DEFINER = `admin`@`%` 
    SQL SECURITY DEFINER
VIEW `StudentsFileBoquim` AS
    select 
        `s`.`id` AS `id`,
        `s`.`inep_id` AS `inep_id`,
        `sd`.`nis` AS `nis`,
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
        `s`.`mother_name` AS `mother`,
        `s`.`father_name` AS `father`,
		`s`.`responsable` AS `responsable`,
        (case `s`.`responsable`
            when '0' then `s`.`father_name`
            when '1' then `s`.`mother_name`
            when '2' then `s`.`responsable_name`
        end) AS `responsable_name`,
		`s`.`responsable_rg`,
		`s`.`responsable_cpf`,
        (case `s`.`responsable_scholarity`
            when '0' then 'Não sabe Ler e Escrever'
            when '1' then 'Sabe Ler e Escrever'
            when '2' then 'Ensino Fundamental Incompleto'
            when '3' then 'Ensino Fundamental Completo'
            when '4' then 'Ensino Médio Incompleto'
            when '5' then 'Ensino Médio Completo'
            when '6' then 'Ensino Superior Incompleto'
            when '7' then 'Ensino Superior Completo'
        end) AS `responsable_scholarity`,
		`s`.`responsable_job`,
        if((`s`.`bf_participator` = 0),
            'Não Participa',
            'Participa') AS `bf_participator`,
		`s`.`food_restrictions` as `food_restrictions`,
		`s`.`responsable_telephone` as `responsable_telephone`,
        if((`s`.`deficiency` = 0),
            'Não Possui',
			CONCAT_WS(': ', 'Possui', 
				CONCAT_WS(',',
					if((`s`.`deficiency_type_blindness` = 1),'Cegueira',null),
					if((`s`.`deficiency_type_low_vision` = 1),'Baixa visão',null),
					if((`s`.`deficiency_type_deafness` = 1),'Surdez',null),
					if((`s`.`deficiency_type_disability_hearing` = 1),'Deficiência Auditiva',null),
					if((`s`.`deficiency_type_deafblindness` = 1),'Surdocegueira',null),
					if((`s`.`deficiency_type_phisical_disability` = 1),'Deficiência Física',null),
					if((`s`.`deficiency_type_intelectual_disability` = 1),'Deficiência Intelectual',null),
					if((`s`.`deficiency_type_multiple_disabilities` = 1),'Deficiência Múltipla',null),
					if((`s`.`deficiency_type_autism` = 1),'Autismo Infantil',null),
					if((`s`.`deficiency_type_aspenger_syndrome` = 1),'Síndrome de Asperger',null),
					if((`s`.`deficiency_type_rett_syndrome` = 1),'Síndrome de Rett',null),
					if((`s`.`deficiency_type_childhood_disintegrative_disorder` = 1),'Transtorno Desintegrativo da Infância',null),
					if((`s`.`deficiency_type_gifted` = 1),'Altas habilidades / Superdotação',null)
				)
			)
		) AS `deficiency`
		
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