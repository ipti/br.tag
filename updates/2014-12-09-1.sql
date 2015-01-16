ALTER TABLE `student_identification` 
ADD COLUMN `responsable` SMALLINT(6) NULL AFTER `last_change`,
ADD COLUMN `responsable_name` VARCHAR(90) NULL AFTER `responsable`,
ADD COLUMN `responsable_rg` VARCHAR(45) NULL AFTER `responsable_name`,
ADD COLUMN `responsable_cpf` VARCHAR(11) NULL AFTER `responsable_rg`,
ADD COLUMN `responsable_scholarity` SMALLINT(6) NULL AFTER `responsable_cpf`,
ADD COLUMN `responsable_job` VARCHAR(45) NULL AFTER `responsable_scholarity`,
ADD COLUMN `bf_participator` TINYINT(1) NULL AFTER `responsable_job`,
ADD COLUMN `food_restrictions` VARCHAR(100) NULL AFTER `bf_participator`;
