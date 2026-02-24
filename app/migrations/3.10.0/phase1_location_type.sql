-- Fase 1: Permitir Lotação na Secretaria de Educação
-- Adiciona suporte para lotação de profissionais na Secretaria Municipal de Educação

-- 1. Adicionar campo para tipo de local
ALTER TABLE `professional_allocation` 
ADD COLUMN `location_type` ENUM('school', 'secretariat', 'other') NOT NULL DEFAULT 'school' AFTER `professional_fk`;

-- 2. Adicionar campo para nome do local (quando não for escola)
ALTER TABLE `professional_allocation` 
ADD COLUMN `location_name` VARCHAR(255) NULL AFTER `location_type`;

-- 3. Remover constraint de foreign key existente
ALTER TABLE `professional_allocation` 
DROP FOREIGN KEY `fk_professional_allocation_school`;

-- 4. Tornar school_inep_fk opcional (permitir NULL)
ALTER TABLE `professional_allocation` 
MODIFY COLUMN `school_inep_fk` VARCHAR(8) NULL;

-- 5. Recriar constraint permitindo NULL
ALTER TABLE `professional_allocation` 
ADD CONSTRAINT `fk_professional_allocation_school` 
FOREIGN KEY (`school_inep_fk`) 
REFERENCES `school_identification` (`inep`) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

-- 6. Adicionar constraint de validação
-- Garante que se location_type = 'school', school_inep_fk deve estar preenchido
-- E se location_type IN ('secretariat', 'other'), location_name deve estar preenchido
ALTER TABLE `professional_allocation` 
ADD CONSTRAINT `chk_location_valid` 
CHECK (
  (location_type = 'school' AND school_inep_fk IS NOT NULL) OR
  (location_type IN ('secretariat', 'other') AND location_name IS NOT NULL)
);
