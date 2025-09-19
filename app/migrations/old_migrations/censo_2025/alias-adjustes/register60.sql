-- Registro 60

drop table temp_alias_60;

-- STEP 1
-- BEGIN: LOAD DATA FROM 2024 NO Registro 60
create temporary table temp_alias_60
select distinct
	register, corder, attr, cdesc, `default`, stable, 2025 as year
from
	`nossasenhoradagloria.tag.ong.br`.edcenso_alias ea
where ea.year = 2024 AND ea.register=60;

delete from edcenso_alias where `year` = 2025 AND register = 60;

INSERT INTO edcenso_alias  (register, corder, attr, cdesc, `default`, stable, `year`) 
select * from temp_alias_60;

-- END: LOAD DATA FROM 2024 NO Registro 60


-- STEP 2
-- BEGIN: Remove Registros 7,8,9 e 10 (Horarios)

select * from edcenso_alias ea where ea.`year` = 2025 and register = 60 order by corder ;

delete from edcenso_alias where corder >= 9 AND corder <= 22 and register = 60 and `year` = 2025;


UPDATE edcenso_alias
	SET corder=(corder+2)
	WHERE register=60 AND corder >= 41 and `year`=2025;

-- END: Remove Registros 7,8,9 e 10 (Horarios)
