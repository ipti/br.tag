ALTER TABLE grade
MODIFY COLUMN grade_unity_modality_fk INT NULL;

ALTER TABLE grade
ADD COLUMN grade_partial_recovery_fk INT NULL;

ALTER TABLE grade
ADD CONSTRAINT fk_grade_partial_recovery
FOREIGN KEY (grade_partial_recovery_fk) REFERENCES grade_partial_recovery(id);
