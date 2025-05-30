-- app/migrations/2024-12-11_add_new_grade_concept_columns/sql.sql

ALTER TABLE grade_results
ADD COLUMN grade_concept_9 VARCHAR(50) NULL,
ADD COLUMN grade_concept_10 VARCHAR(50) NULL,
ADD COLUMN grade_concept_11 VARCHAR(50) NULL,
ADD COLUMN grade_concept_12 VARCHAR(50) NULL;


-- app/migrations/2024-12-07_consent_form_studentsfile/sql


create or replace
algorithm = UNDEFINED view `studentsfile` as
select
    `s`.`id` as `id`,
    `se`.`id` as `enrollment_id`,
    if(isnull(`svm`.`alias`),
    `svm`.`name`,
    `svm`.`alias`) as `stage_name`,
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
    end) as `stage`,
    concat((case when (`svm`.`id` in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38)) then 'NA ' when (`svm`.`id` in (14, 15, 16, 17, 18, 19, 20, 21, 41)) then 'NO ' else '' end),(case when (`svm`.`id` = 1) then 'CRECHE' when (`svm`.`id` = 2) then 'PRÉ-ESCOLA' when (`svm`.`id` = 3) then 'EDUCAÇÃO INFANTIL' when (`svm`.`id` in (4, 14, 25, 30, 35)) then '1' when (`svm`.`id` in (5, 15, 26, 31, 36)) then '2' when (`svm`.`id` in (6, 16, 27, 32, 37)) then '3' when (`svm`.`id` in (7, 17, 28, 33, 38)) then '4' when (`svm`.`id` in (8, 18)) then '5' when (`svm`.`id` in (9, 19)) then '6' when (`svm`.`id` in (10, 20)) then '7' when (`svm`.`id` in (11, 21)) then '8' when (`svm`.`id` = 41) then '9' else '' end),(case when (`svm`.`id` in (1, 2, 3)) then '' when (`svm`.`id` in (4, 5, 6, 7, 8, 9, 10, 11, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38)) then 'ª SÉRIE' when (`svm`.`id` in (14, 15, 16, 17, 18, 19, 20, 21, 41)) then 'º ANO' else 'NA ____________________' end)) as `class`,
    `s`.`inep_id` as `inep_id`,
    `sd`.`nis` as `nis`,
    `s`.`name` as `name`,
    `ec`.`name` as `birth_city`,
    if((`s`.`sex` = 1),
    'Masculino',
    'Feminino') as `gender`,
    (case
        `s`.`color_race` when '1' then 'Branca'
        when '2' then 'Preta'
        when '3' then 'Parda'
        when '4' then 'Amarela'
        when '5' then 'Indígena'
        else 'Não Declarada'
    end) as `color`,
    `s`.`birthday` as `birthday`,
    `eu`.`acronym` as `birth_uf`,
    (case
        `s`.`nationality` when '1' then 'Brasileira'
        when '2' then 'Brasileira: Nascido no exterior ou Naturalizado'
        when '3' then 'Estrangeira'
    end) as `nationality`,
    `en`.`name` as `nation`,
    `sd`.`address` as `address`,
    `eca`.`name` as `adddress_city`,
    `eua`.`acronym` as `address_uf`,
    `sd`.`number` as `number`,
    `sd`.`cep` as `cep`,
    `sd`.`rg_number` as `rg`,
    `sd`.`cpf` as `cpf`,
    `sd`.`cns` as `cns`,
    `sd`.`civil_certification` as `cc`,
    `sd`.`consent_form` as `consent_form`,
    if((`sd`.`civil_certification_type` = 2),
    'Casamento',
    'Nascimento') as `cc_type`,
    `eno`.`name` as `cc_name`,
    `sd`.`civil_register_enrollment_number` as `cc_new`,
    `sd`.`civil_certification_term_number` as `cc_number`,
    `sd`.`civil_certification_book` as `cc_book`,
    `sd`.`civil_certification_sheet` as `cc_sheet`,
    `ecn`.`name` as `cc_city`,
    `eun`.`acronym` as `cc_uf`,
    `s`.`filiation_1` as `mother`,
    `s`.`filiation_1_rg` as `mother_rg`,
    `s`.`filiation_1_cpf` as `mother_cpf`,
    `s`.`filiation_1_job` as `mother_job`,
    `s`.`filiation_2` as `father`,
    `s`.`filiation_2_rg` as `father_rg`,
    `s`.`filiation_2_cpf` as `father_cpf`,
    `s`.`filiation_2_job` as `father_job`,
    `s`.`responsable` as `responsable`,
    (case
        `s`.`responsable` when '0' then concat(`s`.`filiation_2`, ' (PAI)')
        when '1' then concat(`s`.`filiation_1`, ' (MÃE)')
        when '2' then `s`.`responsable_name`
    end) as `responsable_name`,
    `s`.`responsable_rg` as `responsable_rg`,
    `s`.`responsable_cpf` as `responsable_cpf`,
    (case
        `s`.`responsable_scholarity` when '1' then 'Formação Geral'
        when '2' then 'Modalidade Normal (Magistério)'
        when '3' then 'Curso Técnico'
        when '4' then 'Magistério Indígena Modalidade Normal'
    end) as `responsable_scholarity`,
    if(isnull(`s`.`filiation_1_scholarity`),
    'Não Declarado',
    (case
        `s`.`filiation_1_scholarity` when '0' then 'Não sabe Ler e Escrever'
        when '1' then 'Sabe Ler e Escrever'
        when '2' then 'Ensino Fundamental Incompleto'
        when '3' then 'Ensino Fundamental Completo'
        when '4' then 'Ensino Médio Incompleto'
        when '5' then 'Ensino Médio Completo'
        when '6' then 'Ensino Superior Incompleto'
        when '7' then 'Ensino Superior Completo'
    end)) as `mother_scholarity`,
    if(isnull(`s`.`filiation_2_scholarity`),
    'Não Declarado',
    (case
        `s`.`filiation_2_scholarity` when '0' then 'Não sabe Ler e Escrever'
        when '1' then 'Sabe Ler e Escrever'
        when '2' then 'Ensino Fundamental Incompleto'
        when '3' then 'Ensino Fundamental Completo'
        when '4' then 'Ensino Médio Incompleto'
        when '5' then 'Ensino Médio Completo'
        when '6' then 'Ensino Superior Incompleto'
        when '7' then 'Ensino Superior Completo'
    end)) as `father_scholarity`,
    `s`.`responsable_job` as `responsable_job`,
    concat_ws('; ', if((`sd`.`received_cc` = 1), 'Certidão de nascimento', null), if((`sd`.`received_address` = 1), 'Comprovante de endereço', null), if((`sd`.`received_photo` = 1), 'Foto 3x4', null), if((`sd`.`received_nis` = 1), 'Comprovante NIS', null), if((`sd`.`received_history` = 1), 'Histórico', null), if((`sd`.`received_responsable_rg` = 1), 'Cópia RG (responsável)', null), if((`sd`.`received_responsable_cpf` = 1), 'Cópia CPF (responsável)', null)) as `received_documents`,
    if(isnull(`se`.`school_admission_date`),
    null,
    `se`.`school_admission_date`) as `school_admission_date`,
    (case
        `se`.`current_stage_situation` when 0 then 'Primeira matrícula no curso (nível e/ou modalidade de ensino)'
        when 1 then 'Promovido na série anterior do mesmo curso (nível e/ou modalidade de ensino)'
        when 2 then 'Repetente'
    end) as `current_stage_situation`,
    (case
        `se`.`previous_stage_situation` when 0 then 'Não frequentou'
        when 1 then 'Reprovado'
        when 2 then 'Afastado por transferência'
        when 3 then 'Afastado por abandono'
        when 4 then 'Matrícula final em Educação Infantil'
    end) as `previous_stage_situation`,
    if((`s`.`bf_participator` = 0),
    'Não',
    'Sim') as `bf_participator`,
    `s`.`food_restrictions` as `food_restrictions`,
    `se`.`transport_responsable_government` as `transport_responsable_government`,
    `se`.`vehicle_type_van` as `vehicle_type_van`,
    `se`.`vehicle_type_microbus` as `vehicle_type_microbus`,
    `se`.`vehicle_type_bus` as `vehicle_type_bus`,
    `se`.`vehicle_type_bike` as `vehicle_type_bike`,
    `se`.`vehicle_type_animal_vehicle` as `vehicle_type_animal_vehicle`,
    `se`.`vehicle_type_other_vehicle` as `vehicle_type_other_vehicle`,
    `se`.`vehicle_type_waterway_boat_5` as `vehicle_type_waterway_boat_5`,
    `se`.`vehicle_type_waterway_boat_5_15` as `vehicle_type_waterway_boat_5_15`,
    `se`.`vehicle_type_waterway_boat_15_35` as `vehicle_type_waterway_boat_15_35`,
    `se`.`vehicle_type_waterway_boat_35` as `vehicle_type_waterway_boat_35`,
    `se`.`vehicle_type_metro_or_train` as `vehicle_type_metro_or_train`,
    `se`.`status` as `status`,
    if((isnull(`se`.`vehicle_type_bus`)
    or (`se`.`vehicle_type_bus` = 0)),
    'Não',
    concat_ws(': ', 'Sim', concat_ws('; ', if((`se`.`vehicle_type_van` = 1), 'Van / Kombi', if(isnull(`se`.`vehicle_type_van`), 'Van / Kombi', null)), if((`se`.`vehicle_type_microbus` = 1), 'Microônibus', if(isnull(`se`.`vehicle_type_microbus`), 'Microônibus', null)), if((`se`.`vehicle_type_bus` = 1), 'Ônibus', if(isnull(`se`.`vehicle_type_bus`), 'Ônibus', null)), if((`se`.`vehicle_type_bike` = 1), 'Bicicleta', if(isnull(`se`.`vehicle_type_bike`), 'Bicicleta', null)), if((`se`.`vehicle_type_animal_vehicle` = 1), 'Tração animal', if(isnull(`se`.`vehicle_type_animal_vehicle`), 'Tração animal', null)), if((`se`.`vehicle_type_other_vehicle` = 1), 'Rodoviário - Outro', if(isnull(`se`.`vehicle_type_other_vehicle`), 'Rodoviário - Outro', null)), if((`se`.`vehicle_type_waterway_boat_5` = 1), 'Embarcação - Até 5 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_5`), 'Embarcação - Até 5 alunos', null)), if((`se`.`vehicle_type_waterway_boat_5_15` = 1), 'Embarcação - De 5 a 15 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_5_15`), 'Embarcação - De 5 a 15 alunos', null)), if((`se`.`vehicle_type_waterway_boat_15_35` = 1), 'Embarcação - De 15 a 35 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_15_35`), 'Embarcação - De 15 a 35 alunos', null)), if((`se`.`vehicle_type_waterway_boat_35` = 1), 'Embarcação - Acima de 35 alunos', if(isnull(`se`.`vehicle_type_waterway_boat_35`), 'Embarcação - Acima de 35 alunos', null)), if((`se`.`vehicle_type_metro_or_train` = 1), 'Trem / Metrô', if(isnull(`se`.`vehicle_type_metro_or_train`), 'Trem / Metrô', null))))) as `public_transport`,
    `s`.`responsable_telephone` as `responsable_telephone`,
    if((`s`.`deficiency` = 0),
    'Não',
    concat_ws(': ', 'Possui', concat_ws(', ', if((`s`.`deficiency_type_blindness` = 1), 'Cegueira', null), if((`s`.`deficiency_type_low_vision` = 1), 'Baixa visão', null), if((`s`.`deficiency_type_deafness` = 1), 'Surdez', null), if((`s`.`deficiency_type_disability_hearing` = 1), 'Deficiência Auditiva', null), if((`s`.`deficiency_type_deafblindness` = 1), 'Surdocegueira', null), if((`s`.`deficiency_type_phisical_disability` = 1), 'Deficiência Física', null), if((`s`.`deficiency_type_intelectual_disability` = 1), 'Deficiência Intelectual', null), if((`s`.`deficiency_type_multiple_disabilities` = 1), 'Deficiência Múltipla', null), if((`s`.`deficiency_type_autism` = 1), 'Autismo Infantil', null), if((`s`.`deficiency_type_aspenger_syndrome` = 1), 'Síndrome de Asperger', null), if((`s`.`deficiency_type_rett_syndrome` = 1), 'Síndrome de Rett', null), if((`s`.`deficiency_type_childhood_disintegrative_disorder` = 1), 'Transtorno Desintegrativo da Infância', null), if((`s`.`deficiency_type_gifted` = 1), 'Altas habilidades / Superdotação', null)))) as `deficiency`,
    (case
        `sd`.`justice_restriction` when 1 then 'LA - Liberdade Assistida'
        when 2 then 'PSC - Prestação de Serviços Comunitários'
        else 'Não'
    end) as `justice_restriction`
from
    ((((((((((((`student_identification` `s`
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
order by
    `s`.`name`;
