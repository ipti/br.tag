-- =============================================================================
-- TAG Migration v3.14.24 - MACETE lesson plan templates (TCDA-1254)
-- =============================================================================
-- Adds a separate "template" schema so an admin can create reusable MACETE
-- lesson plans that instructors copy into their own editable plan. Templates
-- are network-wide (not tied to a school/year/classroom), so they intentionally
-- do not mirror school_inep_fk/school_year/classroom_fk/territory_context from
-- macete_lesson_plan.
--
-- Also drops macete_lesson_plan.status: plans no longer track a draft/final
-- workflow state, and adds origin_template_fk to trace which template (if any)
-- a plan was created from. Idempotent: safe to run on installations where the
-- objects already exist.

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

-- ---------------------------------------------------------------------------
-- macete_lesson_plan: trace which template (if any) originated a plan.
-- No FK constraint on purpose: this is a soft trace, not a referential rule,
-- so deleting a template later never blocks or cascades onto real plans.
-- ---------------------------------------------------------------------------
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
    'SELECT 1'
);
PREPARE macete_add_origin_template_statement FROM @macete_lesson_plan_origin_template_sql;
EXECUTE macete_add_origin_template_statement;

-- ---------------------------------------------------------------------------
-- macete_lesson_plan: drop the draft/final workflow column, no longer used.
-- ---------------------------------------------------------------------------
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
    'SELECT 1'
);
PREPARE macete_drop_status_statement FROM @macete_lesson_plan_drop_status_sql;
EXECUTE macete_drop_status_statement;
-- No DEALLOCATE PREPARE: MySQL releases it automatically when the session ends,
-- and explicitly deallocating here fails with "Unknown prepared statement handler"
-- on tools/pools that don't guarantee PREPARE and this statement run on the same
-- connection (observed when running this script manually against production).
