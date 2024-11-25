ALTER TABLE grade_unity ADD COLUMN grade_rules_fk int null;

ALTER TABLE grade_unity
ADD CONSTRAINT fk_grade_rules
FOREIGN KEY (grade_rules_fk)
REFERENCES grade_rules(id);

UPDATE grade_unity gu
join grade_rules gr on gr.edcenso_stage_vs_modality_fk = gu.edcenso_stage_vs_modality_fk
SET gu.grade_rules_fk = gr.id
