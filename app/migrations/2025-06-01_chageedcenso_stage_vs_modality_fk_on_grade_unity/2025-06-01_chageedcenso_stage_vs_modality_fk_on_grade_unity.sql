ALTER TABLE grade_unity
MODIFY COLUMN edcenso_stage_vs_modality_fk INT NULL;

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


ALTER TABLE classroom
ADD concept_and_numeric INT NOT NULL DEFAULT 0;

ALTER TABLE grade_rules MODIFY edcenso_stage_vs_modality_fk int NULL;
ALTER TABLE grade_unity MODIFY edcenso_stage_vs_modality_fk int NULL;
