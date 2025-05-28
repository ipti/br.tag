drop table temp_alias;

create temporary table temp_alias
select distinct
	register, corder, attr, cdesc, `default`, stable, 2025 as year
from
	`nossasenhoradagloria.tag.ong.br`.edcenso_alias ea
where ea.year = 2024;

delete from edcenso_alias where `year` = 2025

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) 
select * from temp_alias

-- Registro 10

-- select * from edcenso_alias ea where ea.`year` = 2025 and register = 10

UPDATE edcenso_alias
	SET corder=(corder+1)
	WHERE register=10 AND corder >= 94 and `year`=2025 
 

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) value 
(10, 94, '', 'Quantidade de salas de aula com Cantinho da Leitura para a Educação Infantil e o Ensino fundamental (Anos iniciais)', null, null, 2025)


-- Registro 20

select * from edcenso_alias ea where ea.`year` = 2025 and register = 20 order by corder 

delete from edcenso_alias where corder in (7,8,9,10) and register = 20 and `year` = 2025

UPDATE edcenso_alias
	SET corder=(corder-4)
	WHERE register=20 AND corder >= 11 and `year`=2025 


delete from edcenso_alias where corder in (17, 18, 19) and register = 20 and `year` = 2025

UPDATE edcenso_alias
	SET corder=(corder-3)
	WHERE register=20 AND corder >= 20 and `year`=2025 


delete from edcenso_alias where corder in (17, 18, 19) and register = 20 and `year` = 2025

UPDATE edcenso_alias
	SET corder=(corder+2)
	WHERE register=20 AND corder >= 24 and `year`=2025 

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) values 
(20, 24, '', 'Turma de Educação Especial (classe especial)', null, null, 2025),
(20, 25, '', 'Etapa agregada', null, null, 2025)



