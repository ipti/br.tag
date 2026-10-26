-- =============================================================================
-- TAG Migration v3.15.26 - migrations da release para PRODUCAO
-- =============================================================================
-- Junta, na ordem em que precisam rodar em producao, tudo o que esta versao
-- leva para la. O MACETE ainda nao existe em producao (nenhuma tabela macete_*).
-- Partes ja versionadas em releases anteriores da dev entram aqui iguais as
-- originais. As duas ultimas sao novas e so existem neste arquivo.
--
--   1. 3.14.21/macete.sql - esquema consolidado do MACETE (todas as tabelas macete_*)
--   2. 3.14.22/add_macete_lesson_plan_code.sql - coluna code no plano
--   3. 3.14.23/add_school_territory_context.sql - school_identification.territory_context
--   4. 3.14.24/macete_lesson_plan_templates.sql - planos modelo e remove macete_lesson_plan.status
--   5. 3.14.24/macete_lesson_record_drop_status.sql - remove macete_lesson_record.status
--   6. 3.14.25/macete_lesson_plan_adaptations.sql - campo unico de Adaptacoes
--   7. 3.14.26/inventory_quotation.sql - Fornecedores e Cotacao do Almoxarifado e flags FEAT_INVENTORY_SUPPLIER e FEAT_INVENTORY_QUOTATION (ativas)
--   8. Nova nesta versao - ano letivo (school_year) nos planos modelo, existentes marcados como 2025
--   9. Nova nesta versao - flag de modulo TASK_MACETE, criada desativada
--
-- Nao entram as migrations 3.13.13/macete_lesson_plan*.sql e
-- 3.14.20/macete_lesson_plan_safe.sql, que a 3.14.21 ja consolida
-- (e a 3.13.13 e destrutiva). Tambem nao entram os hotfixes da main
-- (3.13.x), que ja rodaram em producao.
--
-- Diferencas em relacao aos arquivos originais, para rodar pelo
-- composer run migrate (SqlMigrationCommand):
--   * Os comentarios de cada parte foram removidos. O SqlMigrationCommand
--     divide o arquivo por ponto e virgula sem ignorar comentarios.
--   * Os fallbacks SELECT 1 dos PREPARE/EXECUTE viraram DO 0 (mesmo efeito,
--     sem devolver resultado). Um EXECUTE de SELECT deixa um resultado aberto
--     que pode quebrar o comando seguinte no SqlMigrationCommand.
--   * Sem DEALLOCATE PREPARE, como nas migrations 3.14.24 e 3.14.25.
--
-- Todas as partes podem ser rodadas de novo sem erro, inclusive na dev,
-- onde as partes 1 a 7 ja rodaram.
-- =============================================================================

-- ===== 1/9 - 3.14.21/macete.sql

CREATE TABLE IF NOT EXISTS `macete_lesson_plan` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `theme` VARCHAR(255) NOT NULL,
    `school_inep_fk` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `classroom_fk` INT(11) NULL,
    `edcenso_stage_vs_modality_fk` INT(11) NOT NULL,
    `edcenso_discipline_fk` INT(11) NULL,
    `users_fk` INT(11) NOT NULL,
    `school_year` INT(4) NOT NULL,
    `unit` VARCHAR(50) NULL,
    `territory_context` TEXT NULL,
    `knowledge_object` TEXT NULL,
    `evaluation` TEXT NULL,
    `references_text` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lesson_plan_school_year` (`school_inep_fk`, `school_year`),
    KEY `idx_macete_lesson_plan_classroom` (`classroom_fk`),
    KEY `idx_macete_lesson_plan_stage` (`edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lesson_plan_discipline` (`edcenso_discipline_fk`),
    KEY `idx_macete_lesson_plan_user` (`users_fk`),
    CONSTRAINT `fk_macete_lesson_plan_school` FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_classroom` FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_stage` FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_discipline` FOREIGN KEY (`edcenso_discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_user` FOREIGN KEY (`users_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_stage` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `edcenso_stage_vs_modality_fk` INT(11) NOT NULL,
    `edcenso_discipline_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_macete_lesson_plan_stage` (`lesson_plan_fk`, `edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lesson_plan_stage_stage` (`edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lesson_plan_stage_discipline` (`edcenso_discipline_fk`),
    CONSTRAINT `fk_macete_lesson_plan_stage_plan` FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_stage_stage` FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_stage_discipline` FOREIGN KEY (`edcenso_discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @macete_stage_discipline_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_plan_stage'
        AND COLUMN_NAME = 'edcenso_discipline_fk'
);
SET @macete_stage_discipline_sql := IF(
    @macete_stage_discipline_exists = 0,
    'ALTER TABLE `macete_lesson_plan_stage` ADD COLUMN `edcenso_discipline_fk` INT(11) NULL AFTER `edcenso_stage_vs_modality_fk`',
    'DO 0'
);
PREPARE macete_stage_discipline_statement FROM @macete_stage_discipline_sql;
EXECUTE macete_stage_discipline_statement;
DEALLOCATE PREPARE macete_stage_discipline_statement;

UPDATE `macete_lesson_plan_stage` stage
JOIN `macete_lesson_plan` plan ON plan.id = stage.lesson_plan_fk
SET stage.edcenso_discipline_fk = plan.edcenso_discipline_fk
WHERE stage.edcenso_discipline_fk IS NULL
    AND plan.edcenso_discipline_fk IS NOT NULL;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_ability` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `ability_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_macete_lesson_plan_ability` (`lesson_plan_fk`, `ability_fk`),
    KEY `idx_macete_lesson_plan_ability_ability` (`ability_fk`),
    CONSTRAINT `fk_macete_lesson_plan_ability_plan` FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_ability_ability` FOREIGN KEY (`ability_fk`) REFERENCES `course_class_abilities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_section` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `section_type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(150) NULL,
    `target_group` VARCHAR(50) NULL,
    `content` TEXT NULL,
    `position` INT(11) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lesson_plan_section_plan` (`lesson_plan_fk`),
    KEY `idx_macete_lesson_plan_section_type` (`section_type`),
    CONSTRAINT `fk_macete_lesson_plan_section_plan` FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_resource` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `resource_type` VARCHAR(30) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `amount` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lesson_plan_resource_plan` (`lesson_plan_fk`),
    CONSTRAINT `fk_macete_lesson_plan_resource_plan` FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_material` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `material_type` VARCHAR(30) NOT NULL,
    `description` TEXT NULL,
    `file_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lesson_material_plan` (`lesson_plan_fk`),
    CONSTRAINT `fk_macete_lesson_material_plan` FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_record` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `school_inep_fk` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `classroom_fk` INT(11) NOT NULL,
    `edcenso_stage_vs_modality_fk` INT(11) NOT NULL,
    `edcenso_discipline_fk` INT(11) NULL,
    `users_fk` INT(11) NOT NULL,
    `lesson_date` DATE NOT NULL,
    `executed_content` TEXT NOT NULL,
    `methodology_notes` TEXT NULL,
    `evaluation_notes` TEXT NULL,
    `adaptation_notes` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lesson_record_plan` (`lesson_plan_fk`),
    KEY `idx_macete_lesson_record_school_date` (`school_inep_fk`, `lesson_date`),
    KEY `idx_macete_lesson_record_classroom` (`classroom_fk`),
    KEY `idx_macete_lesson_record_stage` (`edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lesson_record_discipline` (`edcenso_discipline_fk`),
    KEY `idx_macete_lesson_record_user` (`users_fk`),
    CONSTRAINT `fk_macete_lesson_record_plan` FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_school` FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_classroom` FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_stage` FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_discipline` FOREIGN KEY (`edcenso_discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_user` FOREIGN KEY (`users_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_record_ability` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_record_fk` INT(11) NOT NULL,
    `ability_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_macete_lesson_record_ability` (`lesson_record_fk`, `ability_fk`),
    KEY `idx_macete_lesson_record_ability_ability` (`ability_fk`),
    CONSTRAINT `fk_macete_lesson_record_ability_record` FOREIGN KEY (`lesson_record_fk`) REFERENCES `macete_lesson_record` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_ability_ability` FOREIGN KEY (`ability_fk`) REFERENCES `course_class_abilities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `macete_lesson_plan_stage` (`lesson_plan_fk`, `edcenso_stage_vs_modality_fk`, `edcenso_discipline_fk`)
SELECT `id`, `edcenso_stage_vs_modality_fk`, `edcenso_discipline_fk`
FROM `macete_lesson_plan`
WHERE `edcenso_stage_vs_modality_fk` IS NOT NULL
    AND `edcenso_discipline_fk` IS NOT NULL;

-- ===== 2/9 - 3.14.22/add_macete_lesson_plan_code.sql

SET @macete_lesson_plan_code_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_plan'
        AND COLUMN_NAME = 'code'
);
SET @macete_lesson_plan_code_sql := IF(
    @macete_lesson_plan_code_exists = 0,
    'ALTER TABLE `macete_lesson_plan` ADD COLUMN `code` VARCHAR(50) NULL AFTER `name`, ADD KEY `idx_macete_lesson_plan_code` (`code`)',
    'DO 0'
);
PREPARE macete_lesson_plan_code_statement FROM @macete_lesson_plan_code_sql;
EXECUTE macete_lesson_plan_code_statement;

-- ===== 3/9 - 3.14.23/add_school_territory_context.sql

SET @school_territory_context_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'school_identification'
        AND COLUMN_NAME = 'territory_context'
);
SET @school_territory_context_sql := IF(
    @school_territory_context_exists = 0,
    'ALTER TABLE `school_identification` ADD COLUMN `territory_context` TEXT NULL AFTER `name`',
    'DO 0'
);
PREPARE school_territory_context_statement FROM @school_territory_context_sql;
EXECUTE school_territory_context_statement;

-- ===== 4/9 - 3.14.24/macete_lesson_plan_templates.sql

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_template` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `code` VARCHAR(50) NULL,
    `theme` VARCHAR(255) NOT NULL,
    `edcenso_stage_vs_modality_fk` INT(11) NOT NULL,
    `edcenso_discipline_fk` INT(11) NULL,
    `created_by_users_fk` INT(11) NULL,
    `unit` VARCHAR(50) NULL,
    `knowledge_object` TEXT NULL,
    `evaluation` TEXT NULL,
    `references_text` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lpt_stage` (`edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lpt_discipline` (`edcenso_discipline_fk`),
    KEY `idx_macete_lpt_code` (`code`),
    CONSTRAINT `fk_macete_lpt_stage` FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lpt_discipline` FOREIGN KEY (`edcenso_discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lpt_user` FOREIGN KEY (`created_by_users_fk`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_template_stage` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_template_fk` INT(11) NOT NULL,
    `edcenso_stage_vs_modality_fk` INT(11) NOT NULL,
    `edcenso_discipline_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_macete_lpt_stage` (`lesson_plan_template_fk`, `edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lpt_stage_stage` (`edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lpt_stage_discipline` (`edcenso_discipline_fk`),
    CONSTRAINT `fk_macete_lpt_stage_template` FOREIGN KEY (`lesson_plan_template_fk`) REFERENCES `macete_lesson_plan_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lpt_stage_stage` FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lpt_stage_discipline` FOREIGN KEY (`edcenso_discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_template_ability` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_template_fk` INT(11) NOT NULL,
    `ability_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_macete_lpt_ability` (`lesson_plan_template_fk`, `ability_fk`),
    KEY `idx_macete_lpt_ability_ability` (`ability_fk`),
    CONSTRAINT `fk_macete_lpt_ability_template` FOREIGN KEY (`lesson_plan_template_fk`) REFERENCES `macete_lesson_plan_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lpt_ability_ability` FOREIGN KEY (`ability_fk`) REFERENCES `course_class_abilities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_template_section` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_template_fk` INT(11) NOT NULL,
    `section_type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(150) NULL,
    `target_group` VARCHAR(50) NULL,
    `content` TEXT NULL,
    `position` INT(11) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lpt_section_template` (`lesson_plan_template_fk`),
    KEY `idx_macete_lpt_section_type` (`section_type`),
    CONSTRAINT `fk_macete_lpt_section_template` FOREIGN KEY (`lesson_plan_template_fk`) REFERENCES `macete_lesson_plan_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_template_resource` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_template_fk` INT(11) NOT NULL,
    `resource_type` VARCHAR(30) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `amount` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lpt_resource_template` (`lesson_plan_template_fk`),
    CONSTRAINT `fk_macete_lpt_resource_template` FOREIGN KEY (`lesson_plan_template_fk`) REFERENCES `macete_lesson_plan_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_material_template` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_template_fk` INT(11) NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `material_type` VARCHAR(30) NOT NULL,
    `description` TEXT NULL,
    `file_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_macete_lmt_template` (`lesson_plan_template_fk`),
    CONSTRAINT `fk_macete_lmt_template` FOREIGN KEY (`lesson_plan_template_fk`) REFERENCES `macete_lesson_plan_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @macete_lesson_plan_origin_template_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_plan'
        AND COLUMN_NAME = 'origin_template_fk'
);
SET @macete_lesson_plan_origin_template_sql := IF(
    @macete_lesson_plan_origin_template_exists = 0,
    'ALTER TABLE `macete_lesson_plan` ADD COLUMN `origin_template_fk` INT(11) NULL AFTER `code`, ADD KEY `idx_macete_lesson_plan_origin_template` (`origin_template_fk`)',
    'DO 0'
);
PREPARE macete_add_origin_template_statement FROM @macete_lesson_plan_origin_template_sql;
EXECUTE macete_add_origin_template_statement;

SET @macete_lesson_plan_status_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_plan'
        AND COLUMN_NAME = 'status'
);
SET @macete_lesson_plan_drop_status_sql := IF(
    @macete_lesson_plan_status_exists > 0,
    'ALTER TABLE `macete_lesson_plan` DROP COLUMN `status`',
    'DO 0'
);
PREPARE macete_drop_status_statement FROM @macete_lesson_plan_drop_status_sql;
EXECUTE macete_drop_status_statement;

-- ===== 5/9 - 3.14.24/macete_lesson_record_drop_status.sql

SET @macete_lesson_record_status_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_record'
        AND COLUMN_NAME = 'status'
);
SET @macete_lesson_record_drop_status_sql := IF(
    @macete_lesson_record_status_exists > 0,
    'ALTER TABLE `macete_lesson_record` DROP COLUMN `status`',
    'DO 0'
);
PREPARE macete_record_drop_status_statement FROM @macete_lesson_record_drop_status_sql;
EXECUTE macete_record_drop_status_statement;

-- ===== 6/9 - 3.14.25/macete_lesson_plan_adaptations.sql

SET @macete_lesson_plan_adaptations_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_plan'
        AND COLUMN_NAME = 'adaptations'
);
SET @macete_lesson_plan_add_adaptations_sql := IF(
    @macete_lesson_plan_adaptations_exists = 0,
    'ALTER TABLE `macete_lesson_plan` ADD COLUMN `adaptations` TEXT NULL AFTER `evaluation`',
    'DO 0'
);
PREPARE macete_plan_add_adaptations_statement FROM @macete_lesson_plan_add_adaptations_sql;
EXECUTE macete_plan_add_adaptations_statement;

SET @macete_lesson_plan_template_adaptations_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_plan_template'
        AND COLUMN_NAME = 'adaptations'
);
SET @macete_lesson_plan_template_add_adaptations_sql := IF(
    @macete_lesson_plan_template_adaptations_exists = 0,
    'ALTER TABLE `macete_lesson_plan_template` ADD COLUMN `adaptations` TEXT NULL AFTER `evaluation`',
    'DO 0'
);
PREPARE macete_template_add_adaptations_statement FROM @macete_lesson_plan_template_add_adaptations_sql;
EXECUTE macete_template_add_adaptations_statement;

DELETE FROM macete_lesson_plan_section
WHERE section_type IN (
    'ADAPTATION_NEURODIVERGENT',
    'ADAPTATION_RECOVERY',
    'ADAPTATION_MULTIGRADE',
    'ADAPTATION_MISSING_MATERIAL'
);

DELETE FROM macete_lesson_plan_template_section
WHERE section_type IN (
    'ADAPTATION_NEURODIVERGENT',
    'ADAPTATION_RECOVERY',
    'ADAPTATION_MULTIGRADE',
    'ADAPTATION_MISSING_MATERIAL'
);

-- ===== 7/9 - 3.14.26/inventory_quotation.sql

CREATE TABLE IF NOT EXISTS `inventory_supplier` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `document` VARCHAR(20) NULL COMMENT 'CNPJ ou CPF',
  `phone` VARCHAR(20) NULL,
  `email` VARCHAR(150) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `inventory_quotation` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0: Aberta, 1: Encerrada',
  `observation` TEXT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `inventory_quotation_item` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `quotation_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  `supplier_id` INT NOT NULL,
  `quoted_value` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`quotation_id`) REFERENCES `inventory_quotation` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_item` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`supplier_id`) REFERENCES `inventory_supplier` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('FEAT_INVENTORY_SUPPLIER', 1, 'Gerenciar Fornecedores do Almoxarifado', NULL, 'N;'),
('FEAT_INVENTORY_QUOTATION', 1, 'Cotação de Preços do Almoxarifado', NULL, 'N;');

INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES
('FEAT_INVENTORY_SUPPLIER', 1, NOW()),
('FEAT_INVENTORY_QUOTATION', 1, NOW());

-- ===== 8/9 - ano letivo (school_year) nos planos modelo

SET @macete_lesson_plan_template_school_year_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_plan_template'
        AND COLUMN_NAME = 'school_year'
);
SET @macete_lesson_plan_template_add_school_year_sql := IF(
    @macete_lesson_plan_template_school_year_exists = 0,
    'ALTER TABLE `macete_lesson_plan_template` ADD COLUMN `school_year` INT(11) NULL AFTER `edcenso_discipline_fk`, ADD KEY `idx_macete_lpt_school_year` (`school_year`)',
    'DO 0'
);
PREPARE macete_template_add_school_year_statement FROM @macete_lesson_plan_template_add_school_year_sql;
EXECUTE macete_template_add_school_year_statement;

UPDATE macete_lesson_plan_template
SET school_year = 2025
WHERE school_year IS NULL;

-- ===== 9/9 - flag de modulo TASK_MACETE

INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('TASK_MACETE', 1, 'Módulo MACETE', NULL, 'N;');

INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES
('TASK_MACETE', 0, NOW());
