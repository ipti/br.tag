-- Adiciona coluna na tabela original de disciplinas
ALTER TABLE edcenso_discipline ADD abbreviation varchar(15);

ALTER TABLE
ADD `alias` varchar(20) COLLATE 'utf8_unicode_ci' NULL AFTER `name`;


UPDATE `edcenso_stage_vs_modality`
 set alias = SUBSTRING(trim(SUBSTRING_INDEX(SUBSTRING_INDEX(name , '-', -1), '-', -1)), 1, 20)
