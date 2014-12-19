ALTER TABLE `student_documents_and_address` 
ADD COLUMN `received_cc` TINYINT(1) NULL AFTER `edcenso_city_fk`,
ADD COLUMN `received_address` TINYINT(1) NULL AFTER `received_cc`,
ADD COLUMN `received_photo` TINYINT(1) NULL AFTER `recived_address`,
ADD COLUMN `received_nis` TINYINT(1) NULL AFTER `received_photo`,
ADD COLUMN `received_history` TINYINT(1) NULL AFTER `received_nis`,
ADD COLUMN `received_responsable_rg` TINYINT(1) NULL AFTER `received_history`,
ADD COLUMN `received_responsable_cpf` TINYINT(1) NULL AFTER `received_responsable_rg`;