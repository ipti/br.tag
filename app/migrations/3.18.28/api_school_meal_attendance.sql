-- =============================================================================
-- TAG Migration v3.18.28 - Módulo de API e presença para a merenda
-- =============================================================================
-- Cria as tabelas do novo módulo de API (app/modules/api):
--
--   1. api_client - credencial de cada aparelho/sistema externo. O
--      client_secret é guardado só em hash (password_hash). Cada cliente pode
--      ficar preso a uma escola (school_inep_fk) e só envia ou lê dados dela.
--   2. school_meal_attendance - presença do dia para a merenda, enviada pelo
--      reconhecimento facial na entrada da escola (um Raspberry Pi por escola).
--      Uma linha por escola e dia. Guarda só a entrega de maior número.
--   3. school_meal_attendance_student - alunos presentes em cada dia.
--
-- Idempotente: só cria o que não existe e não altera dados.

CREATE TABLE IF NOT EXISTS `api_client` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `client_id` VARCHAR(64) NOT NULL,
    `client_secret_hash` VARCHAR(255) NOT NULL,
    `school_inep_fk` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL
        COMMENT 'Escola a que o cliente fica preso. NULL = sem escola (nenhuma rota atual aceita)',
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_used_at` DATETIME NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_api_client_client_id` (`client_id`),
    KEY `idx_api_client_school` (`school_inep_fk`),
    CONSTRAINT `fk_api_client_school`
        FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `school_meal_attendance` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `school_inep_fk` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `date` DATE NOT NULL COMMENT 'Dia da chamada',
    `delivery` INT(11) NOT NULL COMMENT 'Número da entrega no dia. Fica gravada a de maior número',
    `generated_at` DATETIME NULL COMMENT 'Quando o aparelho gerou a lista',
    `total` INT(11) NOT NULL COMMENT 'Total de presentes informado pelo aparelho',
    `reviewed` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'meta.conferida: alguém revisou a lista',
    `complete` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'meta.completo: 0 enquanto ainda há alunos aguardando reconhecimento',
    `without_registration` INT(11) NOT NULL DEFAULT 0 COMMENT 'meta.sem_matricula: deveria ser sempre 0',
    `api_client_fk` INT(11) NULL COMMENT 'Cliente da API que enviou a entrega gravada',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_school_meal_attendance_school_date` (`school_inep_fk`, `date`),
    KEY `idx_school_meal_attendance_date` (`date`),
    KEY `idx_school_meal_attendance_client` (`api_client_fk`),
    CONSTRAINT `fk_school_meal_attendance_school`
        FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_school_meal_attendance_client`
        FOREIGN KEY (`api_client_fk`) REFERENCES `api_client` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `school_meal_attendance_student` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `school_meal_attendance_fk` INT(11) NOT NULL,
    `student_fk` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_school_meal_attendance_student` (`school_meal_attendance_fk`, `student_fk`),
    KEY `idx_school_meal_attendance_student_student` (`student_fk`),
    CONSTRAINT `fk_school_meal_attendance_student_attendance`
        FOREIGN KEY (`school_meal_attendance_fk`) REFERENCES `school_meal_attendance` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_school_meal_attendance_student_student`
        FOREIGN KEY (`student_fk`) REFERENCES `student_identification` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
