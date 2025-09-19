-- Registro 20


drop table temp_alias_20;

-- STEP 1
-- BEGIN: LOAD DATA FROM 2024 NO Registro 20
create temporary table temp_alias_20
select distinct
	register, corder, attr, cdesc, `default`, stable, 2025 as year
from
	`nossasenhoradagloria.tag.ong.br`.edcenso_alias ea
where ea.year = 2024 AND ea.register=20;

delete from edcenso_alias where `year` = 2025 AND register = 20;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) 
select * from temp_alias_20;

-- END: LOAD DATA FROM 2024 NO Registro 10


select * from edcenso_alias ea where ea.`year` = 2025 and register = 20;

-- STEP 2
-- BEGIN: Remove Registros 7,8,9 e 10 (Horarios)

select * from edcenso_alias ea where ea.`year` = 2025 and register = 20 order by corder ;

delete from edcenso_alias where corder in (7,8,9,10) and register = 20 and `year` = 2025;

UPDATE edcenso_alias
	SET corder=(corder-4)
	WHERE register=20 AND corder >= 11 and `year`=2025 ;

-- END: Remove Registros 7,8,9 e 10 (Horarios)
	

-- STEP 3
-- BEGIN: Remove Registros 17,18 e 19 (itinerário formativo)

delete from edcenso_alias where corder in (17, 18, 19) and register = 20 and `year` = 2025;


UPDATE edcenso_alias
	SET corder=(corder-3)
	WHERE register=20 AND corder >= 20 and `year`=2025 ;

-- END: Remove Registros 17,18 e 19 (itinerário formativo)

	
-- STEP 4
-- BEGIN: Altera modalidade para etapa agregada e adiciona classe especial

delete from edcenso_alias where corder = 24 and register = 20 and `year` = 2025;

UPDATE edcenso_alias
	SET corder=(corder+1)
	WHERE register=20 AND corder >= 24 and `year`=2025 ;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(20, 24, '', 'Turma de Educação Especial (classe especial)', null, null, 2025),
(20, 25, '', 'Etapa agregada', null, null, 2025);

-- END: Altera modalidade para etapa agregada e adiciona classe especial


	
-- STEP 5
-- BEGIN: Altera modalidade para etapa agregada e adiciona classe especial

delete from edcenso_alias where corder = 33 and register = 20 and `year` = 2025;


UPDATE edcenso_alias
	SET corder=(corder-1)
	WHERE register=20 AND corder >= 33 and `year`=2025 ;


-- END: Altera modalidade para etapa agregada e adiciona classe especial


-- STEP 5
-- BEGIN: Adicionando substitutos do itinerario formatibo e outros

UPDATE edcenso_alias
	SET corder=(corder+8)
	WHERE register=20 AND corder >= 33 and `year`=2025 ;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(20, 33, '', 'Turma de Formação por Alternância (proposta pedagógica de formação por alternância: tempo-escola e tempo-comunidade)', null, null, 2025),
(20, 34, '', 'Formação geral básica', null, null, 2025),
(20, 35, '', 'Itinerário formativo de aprofundamento', null, null, 2025),
(20, 36, '', 'Itinerário de formação técnica e profissional', null, null, 2025),
(20, 37, '', 'Linguagens e suas tecnologias', null, null, 2025),
(20, 38, '', 'Matemática e suas tecnologias', null, null, 2025),
(20, 39, '', 'Ciências da natureza e suas tecnologias', null, null, 2025),
(20, 40, '', 'Ciências humanas e sociais aplicadas', null, null, 2025);

-- END: Adicionando substitutos do itinerario formatibo e outros


-- STEP 7
-- BEGIN: Removendo components de linguagem

delete from edcenso_alias where corder in (41, 42, 43, 44, 45,46,47,48, 49) and register = 20 and `year` = 2025;

UPDATE edcenso_alias
	SET corder=(corder-9)
	WHERE register=20 AND corder >= 48 and `year`=2025;

-- END: Removendo components de linguagem


-- STEP 8
-- BEGIN: Tipo de curso e código do curso tecnico

UPDATE edcenso_alias
	SET corder=(corder+2)
	WHERE register=20 AND corder >= 41 and `year`=2025 ;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(20, 41, '', 'Tipo do curso do itinerário de formação técnica e profissional', null, null, 2025),
(20, 42, '', 'Código do curso técnico', null, null, 2025);

-- END: Tipo de curso e código do curso tecnico


select * from edcenso_alias where register = 20 and `year` = 2025 order by corder ;



