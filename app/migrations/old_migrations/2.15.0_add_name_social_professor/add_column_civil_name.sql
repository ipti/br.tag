ALTER TABLE `student_identification` ADD `civil_name` VARCHAR(100) NOT NULL AFTER `name`;

-- UPDATE `student_identification` SET `civil_name` = `name`
