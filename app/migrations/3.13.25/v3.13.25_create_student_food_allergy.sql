-- =============================================================================
-- TAG Migration v3.13.25 - Alergias alimentares detalhadas do aluno
-- =============================================================================
-- Detalha por alimento a alergia alimentar do aluno (Acompanhamento de Saúde
-- > Doenças e Distúrbios > Alterações relacionadas à alimentação > Alergias
-- Alimentares). O booleano student_disorder.food_allergies continua existindo
-- e sinaliza que o aluno tem alguma alergia; esta tabela detalha quais.

CREATE TABLE IF NOT EXISTS `student_food_allergy` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `student_fk` INT(11) NOT NULL,
    `allergy_type` VARCHAR(30) NOT NULL,
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_student_food_allergy` (`student_fk`, `allergy_type`),
    KEY `idx_student_food_allergy_type` (`allergy_type`),
    CONSTRAINT `fk_student_food_allergy_student`
        FOREIGN KEY (`student_fk`) REFERENCES `student_identification` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
