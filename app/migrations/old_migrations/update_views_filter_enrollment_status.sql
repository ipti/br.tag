DELIMITER $$

ALTER ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `classroom_enrollment` AS
    SELECT 
        `s`.`id` AS `enrollment`,
        `s`.`name` AS `name`,
        IF((`s`.`sex` = 1), 'M', 'F') AS `sex`,
        `s`.`birthday` AS `birthday`,
        `se`.`current_stage_situation` AS `situation`,
        `se`.`admission_type` AS `admission_type`,
        `se`.`status` AS `status`,
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
        CONCAT(`s`.`filiation_1`,
                '<br>',
                `s`.`filiation_2`) AS `parents`,
        `s`.`deficiency` AS `deficiency`,
        `c`.`id` AS `classroom_id`,
        `c`.`school_year` AS `year`
    FROM
        ((((((`student_identification` `s`
        JOIN `student_documents_and_address` `sd` ON ((`s`.`id` = `sd`.`id`)))
        LEFT JOIN `edcenso_nation` `en` ON ((`s`.`edcenso_nation_fk` = `en`.`id`)))
        LEFT JOIN `edcenso_uf` `euf` ON ((`s`.`edcenso_uf_fk` = `euf`.`id`)))
        LEFT JOIN `edcenso_city` `ec` ON ((`s`.`edcenso_city_fk` = `ec`.`id`)))
        JOIN `student_enrollment` `se` ON ((`s`.`id` = `se`.`student_fk`)))
        JOIN `classroom` `c` ON ((`se`.`classroom_fk` = `c`.`id`)))$$
DELIMITER ;

DELIMITER $$
ALTER ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ata_performance` AS
    SELECT 
        `s`.`name` AS `school`,
        `se`.`status` AS `status`,
        CONCAT(`ec`.`name`, ' - ', `eu`.`acronym`) AS `city`,
        DATE_FORMAT(NOW(), '%d') AS `day`,
        DATE_FORMAT(NOW(), '%m') AS `month`,
        DATE_FORMAT(NOW(), '%Y') AS `year`,
        SUBSTRING_INDEX(`svm`.`name`, ' - ', 1) AS `ensino`,
        `c`.`name` AS `name`,
        (CASE `c`.`turn`
            WHEN 'M' THEN 'Matutino'
            WHEN 'T' THEN 'Vespertino'
            ELSE 'Noturno'
        END) AS `turn`,
        SUBSTRING_INDEX(`svm`.`name`, ' - ', -(1)) AS `serie`,
        `c`.`school_year` AS `school_year`,
        `c`.`id` AS `classroom_id`,
        CONCAT_WS('|',
                IF((`c`.`discipline_biology` = 1),
                    'Biologia',
                    NULL),
                IF((`c`.`discipline_science` = 1),
                    'Ciência',
                    NULL),
                IF((`c`.`discipline_physical_education` = 1),
                    'Educação Física',
                    NULL),
                IF((`c`.`discipline_religious` = 1),
                    'Ensino Religioso',
                    NULL),
                IF((`c`.`discipline_philosophy` = 1),
                    'Filosofia',
                    NULL),
                IF((`c`.`discipline_physics` = 1),
                    'Física',
                    NULL),
                IF((`c`.`discipline_geography` = 1),
                    'Geografia',
                    NULL),
                IF((`c`.`discipline_history` = 1),
                    'História',
                    NULL),
                IF((`c`.`discipline_native_language` = 1),
                    'Lingua Nativa',
                    NULL),
                IF((`c`.`discipline_mathematics` = 1),
                    'Matemática',
                    NULL),
                IF((`c`.`discipline_pedagogical` = 1),
                    'Pedagogia',
                    NULL),
                IF((`c`.`discipline_language_portuguese_literature` = 1),
                    'Português',
                    NULL),
                IF((`c`.`discipline_chemistry` = 1),
                    'Química',
                    NULL),
                IF((`c`.`discipline_arts` = 1),
                    'Ártes',
                    NULL),
                IF((`c`.`discipline_professional_disciplines` = 1),
                    'Disciplina Proficionalizante',
                    NULL),
                IF((`c`.`discipline_foreign_language_spanish` = 1),
                    'Espanhol',
                    NULL),
                IF((`c`.`discipline_social_study` = 1),
                    'Estudo Social',
                    NULL),
                IF((`c`.`discipline_foreign_language_franch` = 1),
                    'Francês',
                    NULL),
                IF((`c`.`discipline_foreign_language_english` = 1),
                    'Inglês',
                    NULL),
                IF((`c`.`discipline_informatics` = 1),
                    'Informática',
                    NULL),
                IF((`c`.`discipline_libras` = 1),
                    'Libras',
                    NULL),
                IF((`c`.`discipline_foreign_language_other` = 1),
                    'Outro Idioma',
                    NULL),
                IF((`c`.`discipline_sociocultural_diversity` = 1),
                    'Sociedade e Cultura',
                    NULL),
                IF((`c`.`discipline_others` = 1),
                    'Outras',
                    NULL)) AS `disciplines`
    FROM
        (((((`classroom` `c`
        JOIN `school_identification` `s` ON ((`c`.`school_inep_fk` = `s`.`inep_id`)))
        JOIN `student_enrollment` `se` ON ((`s`.`inep_id` = `se`.`school_inep_id_fk`)))
        LEFT JOIN `edcenso_city` `ec` ON ((`s`.`edcenso_city_fk` = `ec`.`id`)))
        LEFT JOIN `edcenso_uf` `eu` ON ((`s`.`edcenso_uf_fk` = `eu`.`id`)))
        LEFT JOIN `edcenso_stage_vs_modality` `svm` ON ((`c`.`edcenso_stage_vs_modality_fk` = `svm`.`id`)))$$
DELIMITER ;

DELIMITER $$
ALTER ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `studentsfile` AS
    SELECT 
        `s`.`id` AS `id`,
        `se`.`id` AS `enrollment_id`,
        (CASE `svm`.`stage`
            WHEN '1' THEN 'EDUCAÇÃO INFANTIL'
            WHEN '2' THEN 'ENSINO FUNDAMENTAL'
            WHEN '3' THEN 'ENSINO FUNDAMENTAL'
            WHEN '4' THEN 'ENSINO MÉDIO'
            WHEN '5' THEN 'EDUCAÇÃO PROFISSIONAL'
            WHEN '6' THEN 'EDUCAÇÃO DE JOVENS E ADULTOS'
            WHEN
                '7'
            THEN
                (CASE `svm`.`id`
                    WHEN '56' THEN 'MULTIETAPA'
                    ELSE 'ENSINO FUNDAMENTAL'
                END)
        END) AS `stage`,
        CONCAT((CASE
                    WHEN
                        (`svm`.`id` IN (1 , 2,
                            3,
                            4,
                            5,
                            6,
                            7,
                            8,
                            9,
                            10,
                            11,
                            25,
                            26,
                            27,
                            28,
                            29,
                            30,
                            31,
                            32,
                            33,
                            34,
                            35,
                            36,
                            37,
                            38))
                    THEN
                        'NA '
                    WHEN (`svm`.`id` IN (14 , 15, 16, 17, 18, 19, 20, 21, 41)) THEN 'NO '
                    ELSE ''
                END),
                (CASE
                    WHEN (`svm`.`id` = 1) THEN 'CRECHE'
                    WHEN (`svm`.`id` = 2) THEN 'PRÉ-ESCOLA'
                    WHEN (`svm`.`id` = 3) THEN 'EDUCAÇÃO INFANTIL'
                    WHEN (`svm`.`id` IN (4 , 14, 25, 30, 35)) THEN '1'
                    WHEN (`svm`.`id` IN (5 , 15, 26, 31, 36)) THEN '2'
                    WHEN (`svm`.`id` IN (6 , 16, 27, 32, 37)) THEN '3'
                    WHEN (`svm`.`id` IN (7 , 17, 28, 33, 38)) THEN '4'
                    WHEN (`svm`.`id` IN (8 , 18)) THEN '5'
                    WHEN (`svm`.`id` IN (9 , 19)) THEN '6'
                    WHEN (`svm`.`id` IN (10 , 20)) THEN '7'
                    WHEN (`svm`.`id` IN (11 , 21)) THEN '8'
                    WHEN (`svm`.`id` = 41) THEN '9'
                    ELSE ''
                END),
                (CASE
                    WHEN (`svm`.`id` IN (1 , 2, 3)) THEN ''
                    WHEN
                        (`svm`.`id` IN (4 , 5,
                            6,
                            7,
                            8,
                            9,
                            10,
                            11,
                            25,
                            26,
                            27,
                            28,
                            29,
                            30,
                            31,
                            32,
                            33,
                            34,
                            35,
                            36,
                            37,
                            38))
                    THEN
                        'ª SÉRIE'
                    WHEN (`svm`.`id` IN (14 , 15, 16, 17, 18, 19, 20, 21, 41)) THEN 'º ANO'
                    ELSE 'NA ____________________'
                END)) AS `class`,
        `s`.`inep_id` AS `inep_id`,
        `sd`.`nis` AS `nis`,
        `s`.`name` AS `name`,
        `ec`.`name` AS `birth_city`,
        IF((`s`.`sex` = 1),
            'Masculino',
            'Feminino') AS `gender`,
        (CASE `s`.`color_race`
            WHEN '1' THEN 'Branca'
            WHEN '2' THEN 'Preta'
            WHEN '3' THEN 'Parda'
            WHEN '4' THEN 'Amarela'
            WHEN '5' THEN 'Indígena'
            ELSE 'Não Declarada'
        END) AS `color`,
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
        IF((`sd`.`civil_certification_type` = 2),
            'Casamento',
            'Nascimento') AS `cc_type`,
        `eno`.`name` AS `cc_name`,
        `sd`.`civil_register_enrollment_number` AS `cc_new`,
        `sd`.`civil_certification_term_number` AS `cc_number`,
        `sd`.`civil_certification_book` AS `cc_book`,
        `sd`.`civil_certification_sheet` AS `cc_sheet`,
        `ecn`.`name` AS `cc_city`,
        `eun`.`acronym` AS `cc_uf`,
        `s`.`filiation_1` AS `mother`,
        `s`.`filiation_1_rg` AS `mother_rg`,
        `s`.`filiation_1_cpf` AS `mother_cpf`,
        `s`.`filiation_1_job` AS `mother_job`,
        `s`.`filiation_2` AS `father`,
        `s`.`filiation_2_rg` AS `father_rg`,
        `s`.`filiation_2_cpf` AS `father_cpf`,
        `s`.`filiation_2_job` AS `father_job`,
        `s`.`responsable` AS `responsable`,
        (CASE `s`.`responsable`
            WHEN '0' THEN CONCAT(`s`.`filiation_2`, ' (PAI)')
            WHEN '1' THEN CONCAT(`s`.`filiation_1`, ' (MÃE)')
            WHEN '2' THEN `s`.`responsable_name`
        END) AS `responsable_name`,
        `s`.`responsable_rg` AS `responsable_rg`,
        `s`.`responsable_cpf` AS `responsable_cpf`,
        (CASE `s`.`responsable_scholarity`
            WHEN '0' THEN 'Não sabe Ler e Escrever'
            WHEN '1' THEN 'Sabe Ler e Escrever'
            WHEN '2' THEN 'Ensino Fundamental Incompleto'
            WHEN '3' THEN 'Ensino Fundamental Completo'
            WHEN '4' THEN 'Ensino Médio Incompleto'
            WHEN '5' THEN 'Ensino Médio Completo'
            WHEN '6' THEN 'Ensino Superior Incompleto'
            WHEN '7' THEN 'Ensino Superior Completo'
        END) AS `responsable_scholarity`,
        (CASE `s`.`filiation_1_scholarity`
            WHEN '0' THEN 'Não sabe Ler e Escrever'
            WHEN '1' THEN 'Sabe Ler e Escrever'
            WHEN '2' THEN 'Ensino Fundamental Incompleto'
            WHEN '3' THEN 'Ensino Fundamental Completo'
            WHEN '4' THEN 'Ensino Médio Incompleto'
            WHEN '5' THEN 'Ensino Médio Completo'
            WHEN '6' THEN 'Ensino Superior Incompleto'
            WHEN '7' THEN 'Ensino Superior Completo'
        END) AS `mother_scholarity`,
        (CASE `s`.`filiation_2_scholarity`
            WHEN '0' THEN 'Não sabe Ler e Escrever'
            WHEN '1' THEN 'Sabe Ler e Escrever'
            WHEN '2' THEN 'Ensino Fundamental Incompleto'
            WHEN '3' THEN 'Ensino Fundamental Completo'
            WHEN '4' THEN 'Ensino Médio Incompleto'
            WHEN '5' THEN 'Ensino Médio Completo'
            WHEN '6' THEN 'Ensino Superior Incompleto'
            WHEN '7' THEN 'Ensino Superior Completo'
        END) AS `father_scholarity`,
        `s`.`responsable_job` AS `responsable_job`,
        CONCAT_WS('; ',
                IF((`sd`.`received_cc` = 1),
                    'Certidão de nascimento',
                    NULL),
                IF((`sd`.`received_address` = 1),
                    'Comprovante de endereço',
                    NULL),
                IF((`sd`.`received_photo` = 1),
                    'Foto 3x4',
                    NULL),
                IF((`sd`.`received_nis` = 1),
                    'Comprovante NIS',
                    NULL),
                IF((`sd`.`received_history` = 1),
                    'Histórico',
                    NULL),
                IF((`sd`.`received_responsable_rg` = 1),
                    'Cópia RG (responsável)',
                    NULL),
                IF((`sd`.`received_responsable_cpf` = 1),
                    'Cópia CPF (responsável)',
                    NULL)) AS `received_documents`,
        IF(ISNULL(`se`.`school_admission_date`),
            NULL,
            `se`.`school_admission_date`) AS `school_admission_date`,
        (CASE `se`.`current_stage_situation`
            WHEN 0 THEN 'Primeira matrícula no curso (nível e/ou modalidade de ensino)'
            WHEN 1 THEN 'Promovido na série anterior do mesmo curso (nível e/ou modalidade de ensino)'
            WHEN 2 THEN 'Repetente'
        END) AS `current_stage_situation`,
        (CASE `se`.`previous_stage_situation`
            WHEN 0 THEN 'Não frequentou'
            WHEN 1 THEN 'Reprovado'
            WHEN 2 THEN 'Afastado por transferência'
            WHEN 3 THEN 'Afastado por abandono'
            WHEN 4 THEN 'Matrícula final em Educação Infantil'
        END) AS `previous_stage_situation`,
        IF((`s`.`bf_participator` = 0),
            'Não',
            'Sim') AS `bf_participator`,
        `s`.`food_restrictions` AS `food_restrictions`,
        `se`.`transport_responsable_government` AS `transport_responsable_government`,
        `se`.`vehicle_type_van` AS `vehicle_type_van`,
        `se`.`vehicle_type_microbus` AS `vehicle_type_microbus`,
        `se`.`vehicle_type_bus` AS `vehicle_type_bus`,
        `se`.`vehicle_type_bike` AS `vehicle_type_bike`,
        `se`.`vehicle_type_animal_vehicle` AS `vehicle_type_animal_vehicle`,
        `se`.`vehicle_type_other_vehicle` AS `vehicle_type_other_vehicle`,
        `se`.`vehicle_type_waterway_boat_5` AS `vehicle_type_waterway_boat_5`,
        `se`.`vehicle_type_waterway_boat_5_15` AS `vehicle_type_waterway_boat_5_15`,
        `se`.`vehicle_type_waterway_boat_15_35` AS `vehicle_type_waterway_boat_15_35`,
        `se`.`vehicle_type_waterway_boat_35` AS `vehicle_type_waterway_boat_35`,
        `se`.`vehicle_type_metro_or_train` AS `vehicle_type_metro_or_train`,
        `se`.`status` AS `status`,
        IF((ISNULL(`se`.`vehicle_type_bus`)
                OR (`se`.`vehicle_type_bus` = 0)),
            'Não',
            CONCAT_WS(': ',
                    'Sim',
                    CONCAT_WS('; ',
                            IF((`se`.`vehicle_type_van` = 1),
                                'Van / Kombi',
                                IF(ISNULL(`se`.`vehicle_type_van`),
                                    'Van / Kombi',
                                    NULL)),
                            IF((`se`.`vehicle_type_microbus` = 1),
                                'Microônibus',
                                IF(ISNULL(`se`.`vehicle_type_microbus`),
                                    'Microônibus',
                                    NULL)),
                            IF((`se`.`vehicle_type_bus` = 1),
                                'Ônibus',
                                IF(ISNULL(`se`.`vehicle_type_bus`),
                                    'Ônibus',
                                    NULL)),
                            IF((`se`.`vehicle_type_bike` = 1),
                                'Bicicleta',
                                IF(ISNULL(`se`.`vehicle_type_bike`),
                                    'Bicicleta',
                                    NULL)),
                            IF((`se`.`vehicle_type_animal_vehicle` = 1),
                                'Tração animal',
                                IF(ISNULL(`se`.`vehicle_type_animal_vehicle`),
                                    'Tração animal',
                                    NULL)),
                            IF((`se`.`vehicle_type_other_vehicle` = 1),
                                'Rodoviário - Outro',
                                IF(ISNULL(`se`.`vehicle_type_other_vehicle`),
                                    'Rodoviário - Outro',
                                    NULL)),
                            IF((`se`.`vehicle_type_waterway_boat_5` = 1),
                                'Embarcação - Até 5 alunos',
                                IF(ISNULL(`se`.`vehicle_type_waterway_boat_5`),
                                    'Embarcação - Até 5 alunos',
                                    NULL)),
                            IF((`se`.`vehicle_type_waterway_boat_5_15` = 1),
                                'Embarcação - De 5 a 15 alunos',
                                IF(ISNULL(`se`.`vehicle_type_waterway_boat_5_15`),
                                    'Embarcação - De 5 a 15 alunos',
                                    NULL)),
                            IF((`se`.`vehicle_type_waterway_boat_15_35` = 1),
                                'Embarcação - De 15 a 35 alunos',
                                IF(ISNULL(`se`.`vehicle_type_waterway_boat_15_35`),
                                    'Embarcação - De 15 a 35 alunos',
                                    NULL)),
                            IF((`se`.`vehicle_type_waterway_boat_35` = 1),
                                'Embarcação - Acima de 35 alunos',
                                IF(ISNULL(`se`.`vehicle_type_waterway_boat_35`),
                                    'Embarcação - Acima de 35 alunos',
                                    NULL)),
                            IF((`se`.`vehicle_type_metro_or_train` = 1),
                                'Trem / Metrô',
                                IF(ISNULL(`se`.`vehicle_type_metro_or_train`),
                                    'Trem / Metrô',
                                    NULL))))) AS `public_transport`,
        `s`.`responsable_telephone` AS `responsable_telephone`,
        IF((`s`.`deficiency` = 0),
            'Não',
            CONCAT_WS(': ',
                    'Possui',
                    CONCAT_WS(', ',
                            IF((`s`.`deficiency_type_blindness` = 1),
                                'Cegueira',
                                NULL),
                            IF((`s`.`deficiency_type_low_vision` = 1),
                                'Baixa visão',
                                NULL),
                            IF((`s`.`deficiency_type_deafness` = 1),
                                'Surdez',
                                NULL),
                            IF((`s`.`deficiency_type_disability_hearing` = 1),
                                'Deficiência Auditiva',
                                NULL),
                            IF((`s`.`deficiency_type_deafblindness` = 1),
                                'Surdocegueira',
                                NULL),
                            IF((`s`.`deficiency_type_phisical_disability` = 1),
                                'Deficiência Física',
                                NULL),
                            IF((`s`.`deficiency_type_intelectual_disability` = 1),
                                'Deficiência Intelectual',
                                NULL),
                            IF((`s`.`deficiency_type_multiple_disabilities` = 1),
                                'Deficiência Múltipla',
                                NULL),
                            IF((`s`.`deficiency_type_autism` = 1),
                                'Autismo Infantil',
                                NULL),
                            IF((`s`.`deficiency_type_aspenger_syndrome` = 1),
                                'Síndrome de Asperger',
                                NULL),
                            IF((`s`.`deficiency_type_rett_syndrome` = 1),
                                'Síndrome de Rett',
                                NULL),
                            IF((`s`.`deficiency_type_childhood_disintegrative_disorder` = 1),
                                'Transtorno Desintegrativo da Infância',
                                NULL),
                            IF((`s`.`deficiency_type_gifted` = 1),
                                'Altas habilidades / Superdotação',
                                NULL)))) AS `deficiency`,
        (CASE `sd`.`justice_restriction`
            WHEN 1 THEN 'LA - Liberdade Assistida'
            WHEN 2 THEN 'PSC - Prestação de Serviços Comunitários'
            ELSE 'Não'
        END) AS `justice_restriction`
    FROM
        ((((((((((((`student_identification` `s`
        JOIN `student_documents_and_address` `sd` ON ((`s`.`id` = `sd`.`id`)))
        JOIN `student_enrollment` `se` ON ((`s`.`id` = `se`.`student_fk`)))
        LEFT JOIN `classroom` `c` ON ((`se`.`classroom_fk` = `c`.`id`)))
        LEFT JOIN `edcenso_stage_vs_modality` `svm` ON ((`c`.`edcenso_stage_vs_modality_fk` = `svm`.`id`)))
        LEFT JOIN `edcenso_uf` `eu` ON ((`s`.`edcenso_uf_fk` = `eu`.`id`)))
        LEFT JOIN `edcenso_city` `ec` ON ((`s`.`edcenso_city_fk` = `ec`.`id`)))
        LEFT JOIN `edcenso_nation` `en` ON ((`s`.`edcenso_nation_fk` = `en`.`id`)))
        LEFT JOIN `edcenso_uf` `eua` ON ((`sd`.`edcenso_uf_fk` = `eua`.`id`)))
        LEFT JOIN `edcenso_city` `eca` ON ((`sd`.`edcenso_city_fk` = `eca`.`id`)))
        LEFT JOIN `edcenso_uf` `eun` ON ((`sd`.`notary_office_uf_fk` = `eun`.`id`)))
        LEFT JOIN `edcenso_city` `ecn` ON ((`sd`.`notary_office_city_fk` = `ecn`.`id`)))
        LEFT JOIN `edcenso_notary_office` `eno` ON ((`sd`.`edcenso_notary_office_fk` = `eno`.`cod`)))
    ORDER BY `s`.`name`$$
DELIMITER ;

DELIMITER $$
ALTER ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `classroom_qtd_students` AS
    SELECT 
        `c`.`school_inep_fk` AS `school_inep_fk`,
        `c`.`id` AS `id`,
        `c`.`name` AS `name`,
        CONCAT_WS(' - ',
                CONCAT_WS(':',
                        `c`.`initial_hour`,
                        `c`.`initial_minute`),
                CONCAT_WS(':',
                        `c`.`final_hour`,
                        `c`.`final_minute`)) AS `time`,
        (CASE `c`.`assistance_type`
            WHEN 0 THEN 'NÃO SE APLICA'
            WHEN 1 THEN 'CLASSE HOSPITALAR'
            WHEN 2 THEN 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
            WHEN 3 THEN 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR'
            ELSE 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)'
        END) AS `assistance_type`,
        (CASE `c`.`modality`
            WHEN 1 THEN 'REGULAR'
            WHEN 2 THEN 'ESPECIAL'
            ELSE 'EJA'
        END) AS `modality`,
        `esm`.`name` AS `stage`,
        COUNT(`c`.`id`) AS `students`,
        `c`.`school_year` AS `school_year`,
        `se`.`status` AS `status`
    FROM
        ((`classroom` `c`
        JOIN `student_enrollment` `se` ON ((`c`.`id` = `se`.`classroom_fk`)))
        JOIN `edcenso_stage_vs_modality` `esm` ON ((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)))
    WHERE
        ((`se`.`status` = 1)
            OR ISNULL(`se`.`status`))
    GROUP BY `c`.`id`$$
 
