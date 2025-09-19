-- CRIAR O EDCENSO_ALIAS 2024 PRA IMPORTAR OS BANCOS AUXILIARES

insert into edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
select register, corder, attr, cdesc, `default`, stable, '2024' from edcenso_alias where `year` = '2023';

UPDATE `edcenso_alias` SET
`register` = '301',
`corder` = '98',
`attr` = 'id_email',
`cdesc` = 'Endereço Eletrônico (e-mail)',
`default` = NULL,
`stable` = 'STUDENT_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '301' AND `corder` = '98' AND `attr` = 'id_email' AND `attr` = 'id_email' COLLATE utf8mb4_bin AND `cdesc` = 'Endereço Eletrônico (e-mail)' AND `cdesc` = 'Endereço Eletrônico (e-mail)' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '301',
`corder` = '19',
`attr` = 'deficiency_type_monocular_vision',
`cdesc` = 'Visão Monocular',
`default` = NULL,
`stable` = 'STUDENT_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '301' AND `corder` = '19' AND `attr` = 'deficiency_type_monocular_vision' AND `attr` = 'deficiency_type_monocular_vision' COLLATE utf8mb4_bin AND `cdesc` = 'Visão Monocular' AND `cdesc` = 'Visão Monocular' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '302',
`corder` = '5',
`attr` = 'cpf',
`cdesc` = 'Número do CPF',
`default` = NULL,
`stable` = NULL,
`year` = '2024'
WHERE `register` = '302' AND `corder` = '5' AND `attr` = 'cpf' AND `attr` = 'cpf' COLLATE utf8mb4_bin AND `cdesc` = 'Número do CPF' AND `cdesc` = 'Número do CPF' COLLATE utf8mb4_bin AND `stable` = 'INSTRUCTOR_IDENTIFICATION' AND `stable` = 'INSTRUCTOR_IDENTIFICATION' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '302',
`corder` = '98',
`attr` = 'email',
`cdesc` = 'Endereço Eletrônico (e-mail)',
`default` = NULL,
`stable` = 'INSTRUCTOR_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '302' AND `corder` = '98' AND `attr` = 'email' AND `attr` = 'email' COLLATE utf8mb4_bin AND `cdesc` = 'Endereço Eletrônico (e-mail)' AND `cdesc` = 'Endereço Eletrônico (e-mail)' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;

UPDATE `edcenso_alias` SET
`register` = '302',
`corder` = '19',
`attr` = 'deficiency_type_monocular_vision',
`cdesc` = 'Visão Monocular',
`default` = NULL,
`stable` = 'INSTRUCTOR_IDENTIFICATION',
`year` = '2024'
WHERE `register` = '302' AND `corder` = '19' AND `attr` = 'deficiency_type_monocular_vision' AND `attr` = 'deficiency_type_monocular_vision' COLLATE utf8mb4_bin AND `cdesc` = 'Visão Monocular' AND `cdesc` = 'Visão Monocular' COLLATE utf8mb4_bin AND `year` = '2024' AND `default` IS NULL AND `stable` IS NULL
LIMIT 1;