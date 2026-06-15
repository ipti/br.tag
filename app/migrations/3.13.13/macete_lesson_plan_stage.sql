-- =============================================================================
-- TAG Migration v3.13.13 - MACETE lesson plan stages
-- =============================================================================

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

INSERT IGNORE INTO `macete_lesson_plan_stage` (`lesson_plan_fk`, `edcenso_stage_vs_modality_fk`)
SELECT `id`, `edcenso_stage_vs_modality_fk`
FROM `macete_lesson_plan`
WHERE `edcenso_stage_vs_modality_fk` IS NOT NULL;
