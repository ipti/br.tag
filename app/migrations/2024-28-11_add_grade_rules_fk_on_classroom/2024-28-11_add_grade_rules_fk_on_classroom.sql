insert into classroom_vs_grade_rules (classroom_fk, grade_rules_fk) select c.id, gr.id
from classroom c
JOIN grade_rules gr ON c.edcenso_stage_vs_modality_fk = gr.edcenso_stage_vs_modality_fk;
