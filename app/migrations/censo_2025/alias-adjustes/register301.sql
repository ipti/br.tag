-- Registro 301


drop table temp_alias_301;

-- STEP 1
-- BEGIN: LOAD DATA FROM 2024 NO Registro 30
create temporary table temp_alias_301
select distinct
	register, corder, attr, cdesc, `default`, stable, 2025 as year
from
	`nossasenhoradagloria.tag.ong.br`.edcenso_alias ea
where ea.year = 2024 AND ea.register=301;

delete from edcenso_alias where `year` = 2025 AND register = 301;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) 
select * from temp_alias_301;

-- END: LOAD DATA FROM 2024 NO Registro 30


select * from edcenso_alias ea where ea.`year` = 2025 and register = 301;

-- STEP 2
-- BEGIN: Remove Registros 7,8,9 e 10 (Horarios)

select * from edcenso_alias ea where ea.`year` = 2025 and register = 301 order by corder ;

UPDATE edcenso_alias
	SET corder=(corder+1)
	WHERE register=301 AND corder >= 13 and `year`=2025 ;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(301, 13, '', 'Povo indígena', null, null, 2025);

-- END: Remove Registros 7,8,9 e 10 (Horarios)

-- STEP 2
-- BEGIN: Remove Registros 7,8,9 e 10 (Horarios)

select * from edcenso_alias ea where ea.`year` = 2025 and register = 301 order by corder ;

UPDATE edcenso_alias
	SET corder=(corder+7)
	WHERE register=301 AND corder >= 29 and `year`=2025 ;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(301, 29, '', 'Pessoa física com transtorno(s) que impacta(m) o desenvolvimento da aprendizagem', null, null, 2025),
(301, 30, '', 'Discalculia ou outro transtorno da matemática e raciocínio lógico', null, null, 2025),
(301, 31, '', 'Disgrafia, Disortografia ou outro transtorno da escrita e ortografia', null, null, 2025),
(301, 32, '', 'Dislalia ou outro transtorno da linguagem e comunicação', null, null, 2025),
(301, 33, '', 'Dislexia', null, null, 2025),
(301, 34, '', 'Transtorno do Déficit de Atenção com Hiperatividade (TDAH)', null, null, 2025),
(301, 35, '', 'Transtorno do Processamento Auditivo Central (TPAC)', null, null, 2025);

-- END: Remove Registros 7,8,9 e 10 (Horarios)



-- STEP 2
-- BEGIN: Remove Registros 7,8,9 e 10 (Horarios)

select * from edcenso_alias ea where ea.`year` = 2025 and register = 301 order by corder ;

UPDATE edcenso_alias
	SET corder=(corder+2)
	WHERE register=301 AND corder >= 47 and `year`=2025 ;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(301, 47, '', 'Prova em Braille', null, null, 2025),
(301, 48, '', 'Tempo adicional', null, null, 2025);

-- END: Remove Registros 7,8,9 e 10 (Horarios)


select * from edcenso_alias ea where ea.`year` = 2025 and register = 301 order by corder ;

