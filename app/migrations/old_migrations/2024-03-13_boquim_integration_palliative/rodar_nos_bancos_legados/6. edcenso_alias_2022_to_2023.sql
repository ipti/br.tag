-- AGORA, TRAZER O ANO DE 2023


insert into edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
select register, corder, attr, cdesc, `default`, stable, '2023' from edcenso_alias where `year` = '2022';

update edcenso_alias set attr = '' where year = 2023 and (attr = 'discipline_1_fk'
or attr = 'discipline_2_fk'
or attr = 'discipline_3_fk'
or attr = 'discipline_4_fk'
or attr = 'discipline_5_fk'
or attr = 'discipline_6_fk'
or attr = 'discipline_7_fk'
or attr = 'discipline_8_fk'
or attr = 'discipline_9_fk'
or attr = 'discipline_10_fk'
or attr = 'discipline_11_fk'
or attr = 'discipline_12_fk'
or attr = 'discipline_13_fk'
or attr = 'discipline_14_fk'
or attr = 'discipline_15_fk'
);

delete from edcenso_alias where register = 0 and corder >= 36 and corder <= 84 and year = '2023';

update edcenso_alias set corder = (corder - 37) where corder >= 85 and register = 0 and year = 2023;

insert into edcenso_alias values
(0, 36, null, 'Termo de colaboração (Lei nº 13.019/2014)', null, null, 2023),
(0, 37, null, 'Termo de fomento (Lei nº 13.019/2014)', null, null, 2023),
(0, 38, null, 'Acordo de cooperação (Lei nº 13.019/2014)', null, null, 2023),
(0, 39, null, 'Contrato de prestação de serviço', null, null, 2023),
(0, 40, null, 'Termo de cooperação técnica e financeira', null, null, 2023),
(0, 41, null, 'Contrato de consórcio público/Convênio de cooperação', null, null, 2023),
(0, 42, null, 'Termo de colaboração (Lei nº 13.019/2014)', null, null, 2023),
(0, 43, null, 'Termo de fomento (Lei nº 13.019/2014)', null, null, 2023),
(0, 44, null, 'Acordo de cooperação (Lei nº 13.019/2014)', null, null, 2023),
(0, 45, null, 'Contrato de prestação de serviço', null, null, 2023),
(0, 46, null, 'Termo de cooperação técnica e financeira', null, null, 2023),
(0, 47, null, 'Contrato de consórcio público/Convênio de cooperação', null, null, 2023);

update edcenso_alias set corder = (corder + 1) where corder >= 76 and register = 10 and year = 2023;

insert into edcenso_alias values
(10, 76, 'dependencies_recording_and_editing_studio', 'Estúdio de gravação e edição', null, null, 2023);

update edcenso_alias set corder = (corder + 1) where corder >= 132 and register = 10 and year = 2023;

insert into edcenso_alias values
(10, 132, '', 'Tradutor e Intérprete de Libras para atendimento em outros ambientes da escola que não seja sala de aula', null, null, 2023);

update edcenso_alias set corder = (corder + 1) where corder >= 48 and register = 20 and year = 2023;

insert into edcenso_alias values
(20, 48, null, 'Outra(s) unidade(s) curricular(es) obrigatória(s)', null, null, 2023);

insert into edcenso_alias values
(20, 76, null, 'Classe com ensino desenvolvido com a Língua Brasileira de Sinais – Libras como primeira língua e a língua portuguesa de forma escrita como segunda língua (bilingue para surdos).', 0, null, 2023);

update edcenso_alias set corder = (corder + 1) where corder >= 19 and register = 301 and year = 2023;
update edcenso_alias set corder = (corder + 1) where corder >= 19 and register = 302 and year = 2023;

insert into edcenso_alias values
(301, 19, 'deficiency_type_monocular_vision', 'Visão Monocular', null, null, 2023);

insert into edcenso_alias values
(302, 19, 'deficiency_type_monocular_vision', 'Visão Monocular', null, null, 2023);

update edcenso_alias set corder = (corder + 2) where corder >= 90 and register = 301 and year = 2023;
update edcenso_alias set corder = (corder + 2) where corder >= 90 and register = 302 and year = 2023;

insert into edcenso_alias values
(301, 90, null, 'Educação Bilíngue de Surdos', null, null, 2023),
(301, 91, null, 'Educação e Tecnologia de Informação e Comunicação (TIC)', null, null, 2023);

insert into edcenso_alias values
(302, 90, 'other_courses_bilingual_education_for_the_deaf', 'Educação Bilíngue de Surdos', null, null, 2023),
(302, 91, 'other_courses_education_and_tic', 'Educação e Tecnologia de Informação e Comunicação (TIC)', null, null, 2023);

update edcenso_alias set corder = (corder + 10) where corder >= 24 and register = 50 and year = 2023;

insert into edcenso_alias values
(50, 24, '', 'Código 16', null, null, 2023),
(50, 25, '', 'Código 17', null, null, 2023),
(50, 26, '', 'Código 18', null, null, 2023),
(50, 27, '', 'Código 19', null, null, 2023),
(50, 28, '', 'Código 20', null, null, 2023),
(50, 29, '', 'Código 21', null, null, 2023),
(50, 30, '', 'Código 22', null, null, 2023),
(50, 31, '', 'Código 23', null, null, 2023),
(50, 32, '', 'Código 24', null, null, 2023),
(50, 33, '', 'Código 25', null, null, 2023);

INSERT INTO `edcenso_alias` (`register`, `corder`, `attr`, `cdesc`, `default`, `stable`, `year`)
VALUES ('50', '42', NULL, 'Outra(s) unidade(s) curricular(es) obrigatória(s)', NULL, NULL, '2023');

update edcenso_alias set corder = (corder + 1) where corder >= 21 and register = 60 and year = 2023;

INSERT INTO `edcenso_alias` (`register`, `corder`, `attr`, `cdesc`, `default`, `stable`, `year`)
VALUES ('60', '21', NULL, 'Código do Curso Técnico', NULL, NULL, '2023');