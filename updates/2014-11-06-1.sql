CREATE VIEW `StudentsFileBoquim` AS 
	select `s`.`id` AS `id`,`s`.`name` AS `name`,`ec`.`name` AS `birth_city`,
			if(`s`.`sex`=1, 'Masculino', 'Feminino') as `gender`,
			CASE  `s`.`color_race`
				WHEN '1' THEN 'Branca'
				WHEN '2' THEN 'Preta'
				WHEN '3' THEN 'Parda'
				WHEN '4' THEN 'Amarela'
				WHEN '5' THEN 'Indígena'
				ELSE 'Não Declarada'
			END as `color`,
			`s`.`birthday` AS `birthday`,`eu`.`acronym` AS `birth_uf`,
			`en`.`name` AS `nation`,`sd`.`address` AS `address`,
			`eca`.`name` AS `adddress_city`,`eua`.`acronym` AS `address_uf`,
			`sd`.`cep` AS `cep`,`sd`.`rg_number` AS `rg`,
			`sd`.`civil_certification` AS `cc`,
			`eno`.`name` AS `cc_name`,
			`sd`.`civil_register_enrollment_number` AS `cc_new`,
			`sd`.`civil_certification_term_number` AS `cc_number`,
			`sd`.`civil_certification_book` AS `cc_book`,
			`sd`.`civil_certification_sheet` AS `cc_sheet`,`ecn`.`name` AS `cc_city`,
			`eun`.`acronym` AS `cc_uf`,`s`.`mother_name` AS `mother`,`s`.`father_name` AS `father` 
	from student_identification as s
	join student_documents_and_address as sd on(`s`.`id` = `sd`.`id`)
	left join edcenso_uf as eu on(`s`.`edcenso_uf_fk` = `eu`.`id`)
	left join edcenso_city as ec on(`s`.`edcenso_city_fk` = `ec`.`id`)
	left join edcenso_nation as en on(`s`.`edcenso_nation_fk` = `en`.`id`)
	left join edcenso_uf as eua on(`sd`.`edcenso_uf_fk` = `eua`.`id`)
	left join edcenso_city as eca on(`sd`.`edcenso_city_fk` = `eca`.`id`)
	left join edcenso_uf as eun on(`sd`.`notary_office_uf_fk` = `eun`.`id`)
	left join edcenso_city as ecn on(`sd`.`notary_office_city_fk` = `ecn`.`id`)
	left join edcenso_notary_office as eno on(sd.edcenso_notary_office_fk = eno.id)
order by `s`.`name`;


select * from StudentsFileBoquim;
