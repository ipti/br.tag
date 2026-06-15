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
