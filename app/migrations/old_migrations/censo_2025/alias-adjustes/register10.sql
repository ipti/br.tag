-- Registro 10


drop table temp_alias_10;


-- BEGIN: LOAD DATA FROM 2024 NO Registro 10
create temporary table temp_alias_10
select distinct
	register, corder, attr, cdesc, `default`, stable, 2025 as year
from
	`nossasenhoradagloria.tag.ong.br`.edcenso_alias ea
where ea.year = 2024 AND ea.register=10;

delete from edcenso_alias where `year` = 2025 AND register = 10

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) 
select * from temp_alias_10

-- END: LOAD DATA FROM 2024 NO Registro 10

select * from edcenso_alias ea where ea.`year` = 2025 and register = 10

-- BEGIN: Add Quantidade Salas leitura NO Registro 10

UPDATE edcenso_alias
	SET corder=(corder+1)
	WHERE register=10 AND corder >= 94 and `year`=2025 
 

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) value 
(10, 94, '', 'Quantidade de salas de aula com Cantinho da Leitura para a Educação Infantil e o Ensino fundamental (Anos iniciais)', null, null, 2025)

-- END: Add Quantidade Salas leitura NO Registro 10




