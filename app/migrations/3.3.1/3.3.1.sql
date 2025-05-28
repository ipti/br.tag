UPDATE edcenso_alias
SET attr = 'dependencies_reading_corners'
WHERE corder = 94;

ALTER TABLE school_structure
ADD COLUMN dependencies_reading_corners INTEGER DEFAULT 0;
