ALTER TABLE edcenso_discipline
ADD COLUMN requires_exam TINYINT(1) NOT NULL DEFAULT 1

ALTER TABLE edcenso_discipline
ADD COLUMN report_text VARCHAR(25) NULL;
