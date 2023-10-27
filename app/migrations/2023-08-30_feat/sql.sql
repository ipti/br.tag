
ALTER TABLE student_identification ADD uf varchar(2) NULL AFTER nationality;
ALTER TABLE classroom ADD gov_id tinytext NULL AFTER inep_id;
ALTER table student_documents_and_address add gov_id tinytext null after student_fk;

INSERT into edcenso_stage_vs_modality (name, stage) values ('Atendimento Educacional Especializado', 2);
INSERT into edcenso_stage_vs_modality (name, stage) values ('Complementação Educacional', 7);
INSERT into edcenso_stage_vs_modality (name, stage) values ('Ensino Médio Integrado A Educação Profissional', 4);
