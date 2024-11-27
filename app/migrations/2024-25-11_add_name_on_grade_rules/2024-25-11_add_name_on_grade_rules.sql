ALTER TABLE grade_rules ADD COLUMN name varchar(100) NULL;

UPDATE grade_rules gr
join edcenso_stage_vs_modality esvm on gr.edcenso_stage_vs_modality_fk = esvm.id
SET gr.name = CONCAT('Estrutura de unidades da etapa ', esvm.name);
