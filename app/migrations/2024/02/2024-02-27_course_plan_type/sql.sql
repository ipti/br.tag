ALTER TABLE course_class ADD COLUMN `type` VARCHAR(100);
RENAME TABLE course_class_has_class_type TO _course_class_has_class_type;
RENAME TABLE course_class_types TO _course_class_types;
