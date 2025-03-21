
UPDATE student_enrollment
SET school_admission_date = STR_TO_DATE(school_admission_date, '%d/%m/%Y')
WHERE school_admission_date IS NOT NULL;

ALTER TABLE student_enrollment MODIFY COLUMN school_admission_date DATE NULL;

UPDATE student_enrollment SET
enrollment_date = school_admission_date
WHERE school_admission_date IS NOT NULL;
