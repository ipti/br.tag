create table classroom_vs_grade_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_fk INT NOT NULL,
    grade_rules_fk INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT classroom_vs_grade_rules_fk_classroom FOREIGN KEY (classroom_fk) REFERENCES classroom(id),
    CONSTRAINT classroom_vs_grade_rules_fk_grade_rules FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id)
);

ALTER TABLE grade_rules ADD COLUMN name varchar(100) NULL;

UPDATE grade_rules gr
join edcenso_stage_vs_modality esvm on gr.edcenso_stage_vs_modality_fk = esvm.id
SET gr.name = CONCAT('Estrutura de unidades da etapa ', esvm.name);

ALTER TABLE grade_unity ADD COLUMN grade_rules_fk int null;

ALTER TABLE grade_unity
ADD CONSTRAINT fk_grade_rules
FOREIGN KEY (grade_rules_fk)
REFERENCES grade_rules(id);

UPDATE grade_unity gu
join grade_rules gr on gr.edcenso_stage_vs_modality_fk = gu.edcenso_stage_vs_modality_fk
SET gu.grade_rules_fk = gr.id;


CREATE TABLE grade_rules_vs_edcenso_stage_vs_modality (
    id INT AUTO_INCREMENT PRIMARY KEY,
    edcenso_stage_vs_modality_fk INT NOT NULL,
    grade_rules_fk INT NOT NULL,
    FOREIGN KEY (edcenso_stage_vs_modality_fk) REFERENCES edcenso_stage_vs_modality(id),
    FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id)
);

CREATE INDEX idx_edcenso_stage_vs_modality_fk
ON grade_rules_vs_edcenso_stage_vs_modality (edcenso_stage_vs_modality_fk);

CREATE INDEX idx_grade_rules_fk
ON grade_rules_vs_edcenso_stage_vs_modality (grade_rules_fk);


INSERT INTO grade_rules_vs_edcenso_stage_vs_modality (edcenso_stage_vs_modality_fk, grade_rules_fk)
SELECT
    e.id AS edcenso_stage_vs_modality_fk,
    g.id AS grade_rules_fk
FROM
    edcenso_stage_vs_modality e
JOIN
    grade_rules g
ON
    e.id= g.edcenso_stage_vs_modality_fk ;

ALTER TABLE grade_rules MODIFY edcenso_stage_vs_modality_fk int NULL;
ALTER TABLE grade_unity MODIFY edcenso_stage_vs_modality_fk int NULL;

insert into classroom_vs_grade_rules (classroom_fk, grade_rules_fk) select c.id, gr.id
from classroom c
JOIN grade_rules gr ON c.edcenso_stage_vs_modality_fk = gr.edcenso_stage_vs_modality_fk;

INSERT INTO classroom_vs_grade_rules (classroom_fk, grade_rules_fk)
SELECT DISTINCT se.classroom_fk, gr.id
FROM  student_enrollment se
JOIN grade_rules gr ON se.edcenso_stage_vs_modality_fk = gr.edcenso_stage_vs_modality_fk;

