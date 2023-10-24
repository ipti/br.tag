-- Adiciona coluna alias na tabela edcenso_stage_vs_modality
ALTER TABLE edcenso_stage_vs_modality ADD alias varchar(15);

UPDATE `edcenso_stage_vs_modality`
 set alias = SUBSTRING(trim(SUBSTRING_INDEX(SUBSTRING_INDEX(name , '-', -1), '-', -1)), 1, 20)
