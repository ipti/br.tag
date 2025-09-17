-- ADICIONANDO COLUNA YEAR
ALTER TABLE edcenso_alias ADD COLUMN year SMALLINT;
UPDATE edcenso_alias SET year = 2019;

-- ADICIONANDO REGISTROS PARA O ANO DE 2020
INSERT INTO edcenso_alias (`register`, `corder`, `attr`, `cdesc`, `default`, `stable`, `year`) SELECT `register`, `corder`, `attr`, `cdesc`, `default`, `stable`, 2020 FROM edcenso_alias WHERE `year` = 2019;

-- REGISTRO 10 ANO 2020
UPDATE edcenso_alias SET attr = 'sewage_public' WHERE year = 2020 AND register = 10 AND corder = 27;
UPDATE edcenso_alias SET attr = 'sewage_fossa' WHERE year = 2020 AND register = 10 AND corder = 28;
UPDATE edcenso_alias SET attr = 'sewage_fossa_common' WHERE year = 2020 AND register = 10 AND corder = 29;
UPDATE edcenso_alias SET attr = 'garbage_destination_public' WHERE year = 2020 AND register = 10 AND corder = 34;
UPDATE edcenso_alias SET attr = 'internet_access_connected_desktop' WHERE year = 2020 AND register = 10 AND corder = 107;
UPDATE edcenso_alias SET attr = 'internet_access_connected_personaldevice' WHERE year = 2020 AND register = 10 AND corder = 108;
UPDATE edcenso_alias SET corder = corder + 2 WHERE year = 2020 AND register = 10 AND corder >= 126;
INSERT INTO edcenso_alias (register, corder, attr, cdesc, year) VALUES (10, 126, '', 'Vice-diretor(a) ou diretor(a) adjunto(a), profissionais responsáveis pela gestão administrativa e/ou financeira', 2020);
INSERT INTO edcenso_alias (register, corder, attr, cdesc, year) VALUES (10, 127, '', 'Orientador(a) comunitário(a) ou assistente social', 2020);


-- REGISTRO 30 ANO 2020
DELETE FROM edcenso_alias WHERE year = 2020 AND register = 301 AND corder = 41;
UPDATE edcenso_alias SET corder = corder - 1 WHERE year = 2020 AND register = 301 AND corder >= 42;
DELETE FROM edcenso_alias WHERE year = 2020 AND register = 301 AND corder = 80 AND cdesc = 'Pós-Graduação em gestão escolar';
DELETE FROM edcenso_alias WHERE year = 2020 AND register = 302 AND corder = 41;
UPDATE edcenso_alias SET corder = corder - 1 WHERE year = 2020 AND register = 302 AND corder >= 42;
DELETE FROM edcenso_alias WHERE year = 2020 AND register = 302 AND corder = 80 AND cdesc = 'Pós-Graduação em gestão escolar';
UPDATE edcenso_alias SET attr = '' WHERE year = 2020 AND register = 302 AND corder = 47;


-- REGISTRO 40 ANO 2020
DELETE FROM edcenso_alias WHERE year = 2020 AND register = 40 AND corder = 7;
UPDATE edcenso_alias SET corder = corder - 1 WHERE year = 2020 AND register = 40 AND corder >= 8;


-- ADICIONANDO REGISTROS PARA O ANO DE 2021
INSERT INTO edcenso_alias (`register`, `corder`, `attr`, `cdesc`, `default`, `stable`, `year`) SELECT `register`, `corder`, `attr`, `cdesc`, `default`, `stable`, 2021 FROM edcenso_alias WHERE `year` = 2020;