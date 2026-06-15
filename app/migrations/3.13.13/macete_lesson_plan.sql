-- =============================================================================
-- TAG Migration v3.13.13 - MACETE lesson plans and lesson records
-- =============================================================================
drop table if exists macete_lesson_material;
drop table if exists macete_lesson_plan_section;
drop table if exists macete_lesson_plan_ability;

drop table if exists macete_lesson_plan_resource;
drop table if exists macete_lesson_record_ability;
drop table if exists macete_lesson_record;
drop table if exists macete_lesson_plan;


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
    CONSTRAINT `fk_macete_lesson_plan_school`
        FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_classroom`
        FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_stage`
        FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_discipline`
        FOREIGN KEY (`edcenso_discipline_fk`) REFERENCES `edcenso_discipline` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_user`
        FOREIGN KEY (`users_fk`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_ability` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `ability_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_macete_lesson_plan_ability` (`lesson_plan_fk`, `ability_fk`),
    KEY `idx_macete_lesson_plan_ability_ability` (`ability_fk`),
    CONSTRAINT `fk_macete_lesson_plan_ability_plan`
        FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_ability_ability`
        FOREIGN KEY (`ability_fk`) REFERENCES `course_class_abilities` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `macete_lesson_plan_stage` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lesson_plan_fk` INT(11) NOT NULL,
    `edcenso_stage_vs_modality_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_macete_lesson_plan_stage` (`lesson_plan_fk`, `edcenso_stage_vs_modality_fk`),
    KEY `idx_macete_lesson_plan_stage_stage` (`edcenso_stage_vs_modality_fk`),
    CONSTRAINT `fk_macete_lesson_plan_stage_plan`
        FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_plan_stage_stage`
        FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
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
    CONSTRAINT `fk_macete_lesson_plan_section_plan`
        FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
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
    CONSTRAINT `fk_macete_lesson_plan_resource_plan`
        FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
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
    CONSTRAINT `fk_macete_lesson_material_plan`
        FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
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
    CONSTRAINT `fk_macete_lesson_record_plan`
        FOREIGN KEY (`lesson_plan_fk`) REFERENCES `macete_lesson_plan` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_school`
        FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_classroom`
        FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_stage`
        FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_discipline`
        FOREIGN KEY (`edcenso_discipline_fk`) REFERENCES `edcenso_discipline` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_user`
        FOREIGN KEY (`users_fk`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
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
    CONSTRAINT `fk_macete_lesson_record_ability_record`
        FOREIGN KEY (`lesson_record_fk`) REFERENCES `macete_lesson_record` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_macete_lesson_record_ability_ability`
        FOREIGN KEY (`ability_fk`) REFERENCES `course_class_abilities` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
