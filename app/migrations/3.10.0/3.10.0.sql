-- TAG Migration: Módulo de Lotação de Profissionais
-- Versão 3.10.0

-- 1. Estrutura da Tabela de Lotação
CREATE TABLE IF NOT EXISTS `professional_allocation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `professional_fk` INT(11) NOT NULL,
  `school_inep_fk` VARCHAR(8) NOT NULL,
  `role` INT(11) NOT NULL,
  `contract_type` INT(11) NOT NULL,
  `workload` INT(11) NOT NULL,
  `school_year` INT(11) NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_professional_allocation_professional` (`professional_fk`),
  KEY `fk_professional_allocation_school` (`school_inep_fk`),
  CONSTRAINT `fk_professional_allocation_professional` FOREIGN KEY (`professional_fk`) REFERENCES `professional` (`id_professional`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_professional_allocation_school` FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 2. Permissões e Feature Flags
-- Add to AuthItem
-- TYPE_OPERATION = 0, TYPE_TASK = 1
INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES 
('FEAT_PROFESSIONAL_LIST', 0, 'Lotação de Profissionais', NULL, 'N;'),
('TASK_PROFESSIONAL_MANAGE', 1, 'Gestão de lotação de profissionais não docentes', NULL, 'N;');

-- Vincular Task à Feature (auth_item_child)
INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES 
('TASK_PROFESSIONAL_MANAGE', 'FEAT_PROFESSIONAL_LIST');

-- Add to Feature Flags
INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES 
('FEAT_PROFESSIONAL_LIST', 1, NOW()),
('TASK_PROFESSIONAL_MANAGE', 1, NOW());

-- 3. Atribuição de permissões básicas para papéis (AuthItemChild)
INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES 
('admin', 'TASK_PROFESSIONAL_MANAGE'),
('manager', 'TASK_PROFESSIONAL_MANAGE'),
('reader', 'TASK_PROFESSIONAL_MANAGE');



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


-- Migração de profissionais para alocação com mapeamento de especialidade
-- Mapeia o campo 'speciality' da tabela professional para o 'role' da tabela professional_allocation

INSERT INTO professional_allocation (
    professional_fk, 
    school_inep_fk, 
    school_year, 
    location_type, 
    role, 
    contract_type, 
    workload, 
    created_at, 
    updated_at
)
SELECT 
    id_professional, 
    inep_id_fk, 
    YEAR(NOW()), 
    'school',
    CASE 
        WHEN speciality LIKE '%Horta%' OR speciality LIKE '%Plantio%' OR speciality LIKE '%Agricultura%' THEN 1
        WHEN speciality LIKE '%Administrativo%' OR speciality LIKE '%Auxiliar Adm%' THEN 2
        WHEN speciality LIKE '%Serviços Gerais%' OR speciality LIKE '%ASG%' OR speciality LIKE '%Zelador%' OR speciality LIKE '%Limpeza%' THEN 3
        WHEN speciality LIKE '%Bibliotec%' THEN 4
        WHEN speciality LIKE '%Bombeiro%' THEN 5
        WHEN speciality LIKE '%Coordenador%' THEN 6
        WHEN speciality LIKE '%Fonoaud%' THEN 7
        WHEN speciality LIKE '%Nutri%' THEN 8
        WHEN speciality LIKE '%Psic%' THEN 9
        WHEN speciality LIKE '%Cozin%' OR speciality LIKE '%Merend%' THEN 10
        WHEN speciality LIKE '%Apoio%' OR speciality LIKE '%Pedag%' THEN 11
        WHEN speciality LIKE '%Secretár%' THEN 12
        WHEN speciality LIKE '%Seguran%' OR speciality LIKE '%Vigia%' OR speciality LIKE '%Guarda%' THEN 13
        WHEN speciality LIKE '%Monitor%' THEN 14
        WHEN speciality LIKE '%Braille%' THEN 15
        ELSE 9999 -- Default para ROLE_UNDEFINED se não encontrar correspondência (força validação no Sagres)
    END as role_mapped,
    99,  -- Contrato padrão (CONTRACT_UNDEFINED) - força correção
    40, -- Carga horária padrão
    NOW(), 
    NOW()
FROM professional 
WHERE inep_id_fk IS NOT NULL AND inep_id_fk != ''
-- Evita duplicatas se rodar mais de uma vez no mesmo ano
AND id_professional NOT IN (
    SELECT professional_fk 
    FROM professional_allocation 
    WHERE school_year = YEAR(NOW())
);


ALTER TABLE professional_allocation ADD COLUMN status TINYINT(1) NOT NULL DEFAULT 1;

-- Add school_year column to grade_rules table
ALTER TABLE grade_rules ADD COLUMN school_year INT(4) NULL AFTER name;
