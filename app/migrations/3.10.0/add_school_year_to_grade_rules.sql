-- Add school_year column to grade_rules table
ALTER TABLE grade_rules ADD COLUMN school_year INT(4) NULL AFTER name;
