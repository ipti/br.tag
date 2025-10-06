-- Registro 50

drop table temp_alias_50;

-- STEP 1
-- BEGIN: LOAD DATA FROM 2024 NO Registro 50
create temporary table temp_alias_50
select distinct
	register, corder, attr, cdesc, `default`, stable, 2025 as year
from
	`nossasenhoradagloria.tag.ong.br`.edcenso_alias ea
where ea.year = 2024 AND ea.register=50;

delete from edcenso_alias where `year` = 2025 AND register = 50;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) 
select * from temp_alias_50;

-- END: LOAD DATA FROM 2024 NO Registro 50


-- STEP 2
-- BEGIN: Remove Registros 7,8,9 e 10 (Horarios)

select * from edcenso_alias ea where ea.`year` = 2025 and register = 50 order by corder ;

delete from edcenso_alias where corder >= 34 and register = 50 and `year` = 2025;


-- END: Remove Registros 7,8,9 e 10 (Horarios)



INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(50, 34, '', 'Linguagens e suas tecnologias', null, null, 2025),
(50, 35, '', 'Matemática e suas tecnologias', null, null, 2025),
(50, 36, '', 'Ciências da natureza e suas tecnologias', null, null, 2025),
(50, 37, '', 'Ciências humanas e sociais aplicadas', null, null, 2025),
(50, 38, '', 'Profissional escolar leciona no Itinerário de formação técnica e profissional (IFTP)', null, null, 2025);