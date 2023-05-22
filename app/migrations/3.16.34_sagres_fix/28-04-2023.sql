ALTER TABLE lunch_menu ADD turn VARCHAR(45) DEFAULT "M" NOT NULL;


/*
 * These commands add a new column 'speciality' to the 'professional' table 
 * and copy the corresponding values from the 'edcenso_professional_education_course' related table.
 */
ALTER TABLE professional
ADD COLUMN speciality varchar(100) COLLATE utf8_unicode_ci NOT NULL AFTER cpf_professional;

UPDATE professional p
JOIN edcenso_professional_education_course e ON p.speciality_fk = e.id
SET p.speciality = e.name;


ALTER TABLE professional
DROP FOREIGN KEY professional_edcenso_professional_education_course_fk;


ALTER TABLE professional
drop column  speciality_fk;