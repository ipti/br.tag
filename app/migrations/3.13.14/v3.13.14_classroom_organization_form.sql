-- TAG Migration v3.13.14 -- Campo organization_form na turma
--
-- Adiciona o campo "Forma de organizacao da turma" ao modelo de turma,
-- permitindo que o Registro 20 do Educacenso 2026 leia diretamente o valor
-- informado no cadastro da turma, sem inferencia por etapa.

START TRANSACTION;

ALTER TABLE classroom
    ADD COLUMN `organization_form` TINYINT(1) NULL
        COMMENT '1=serie/ano, 2=periodos semestrais, 3=ciclos, 4=grupos nao seriados, 5=modulos'
    AFTER `is_alternance`;

COMMIT;


--  Auto-generated SQL script #202606151723
UPDATE edcenso_alias ea
	SET ea.attr='organization_form',
    ea.default='1'
	WHERE ea.register=20 AND ea.corder=28 AND ea.attr IS NULL AND ea.cdesc='Forma de organizacao da turma' AND ea.`default` IS NULL AND ea.stable IS NULL AND ea.`year`=2026;
