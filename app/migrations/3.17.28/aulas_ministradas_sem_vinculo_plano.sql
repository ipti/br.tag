-- =============================================================================
-- TAG Migration v3.17.28 - Aulas ministradas e registros MACETE sem depender do plano
-- =============================================================================
-- Antes: class_contents.course_class_fk e macete_lesson_record.lesson_plan_fk eram
-- FKs com ON DELETE CASCADE. Apagar uma aula do plano (o save do plano apaga as
-- aulas que não vêm no POST) ou o plano MACETE apagava junto as aulas ministradas
-- e os registros de aula.
--
-- Agora:
--   1. as FKs em cascata saem, course_class_fk e lesson_plan_fk ficam só como id
--      (para achar o plano enquanto ele existir),
--   2. a aula ministrada guarda uma cópia do plano: nome, dono, etapa, componente,
--      ordem, conteúdo, metodologia e habilidades (tabela class_contents_ability),
--   3. o registro MACETE guarda uma cópia de todos os campos do plano MACETE
--      (listas de etapas, seções, recursos, materiais e habilidades em JSON),
--   4. as cópias são preenchidas a partir dos planos que existem hoje.
--
-- Idempotente: pode rodar mais de uma vez. Só preenche cópias ainda vazias.
-- class_contents é grande (centenas de milhares de linhas por município): rode
-- fora do horário de uso.

-- -----------------------------------------------------------------------------
-- 1. class_contents: remove a FK para course_class e deixa course_class_fk opcional
-- -----------------------------------------------------------------------------
SET @cc_fk_name := (
    SELECT CONSTRAINT_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'class_contents'
        AND COLUMN_NAME = 'course_class_fk'
        AND REFERENCED_TABLE_NAME = 'course_class'
    LIMIT 1
);
SET @cc_fk_sql := IF(
    @cc_fk_name IS NULL,
    'SELECT 1',
    CONCAT('ALTER TABLE `class_contents` DROP FOREIGN KEY `', @cc_fk_name, '`')
);
PREPARE cc_fk_statement FROM @cc_fk_sql;
EXECUTE cc_fk_statement;

ALTER TABLE `class_contents` MODIFY `course_class_fk` INT NULL;

-- -----------------------------------------------------------------------------
-- 2. class_contents: colunas com a cópia do plano
-- -----------------------------------------------------------------------------
SET @cc_snapshot_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'class_contents'
        AND COLUMN_NAME = 'course_plan_id'
);
SET @cc_snapshot_sql := IF(
    @cc_snapshot_exists = 0,
    'ALTER TABLE `class_contents`
        ADD COLUMN `course_plan_id` INT NULL COMMENT ''Id do plano (sem FK)'' AFTER `course_class_fk`,
        ADD COLUMN `course_plan_name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `course_plan_id`,
        ADD COLUMN `course_plan_users_id` INT NULL COMMENT ''Dono do plano (sem FK)'' AFTER `course_plan_name`,
        ADD COLUMN `course_plan_stage_id` INT NULL COMMENT ''Etapa do plano (sem FK)'' AFTER `course_plan_users_id`,
        ADD COLUMN `course_plan_discipline_id` INT NULL COMMENT ''Componente do plano (sem FK)'' AFTER `course_plan_stage_id`,
        ADD COLUMN `class_order` INT NULL AFTER `course_plan_discipline_id`,
        ADD COLUMN `content` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `class_order`,
        ADD COLUMN `methodology` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `content`,
        ADD KEY `idx_class_contents_course_plan` (`course_plan_id`),
        ADD KEY `idx_class_contents_plan_user` (`schedule_fk`, `course_plan_users_id`)',
    'SELECT 1'
);
PREPARE cc_snapshot_statement FROM @cc_snapshot_sql;
EXECUTE cc_snapshot_statement;

CREATE TABLE IF NOT EXISTS `class_contents_ability` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `class_contents_fk` INT NOT NULL,
    `course_class_ability_fk` INT NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_class_contents_ability` (`class_contents_fk`, `course_class_ability_fk`),
    KEY `idx_class_contents_ability_ability` (`course_class_ability_fk`),
    CONSTRAINT `fk_class_contents_ability_contents` FOREIGN KEY (`class_contents_fk`)
        REFERENCES `class_contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_class_contents_ability_ability` FOREIGN KEY (`course_class_ability_fk`)
        REFERENCES `course_class_abilities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 3. class_contents: preenche a cópia a partir das aulas e planos que existem
-- -----------------------------------------------------------------------------
UPDATE `class_contents` cc
JOIN `course_class` c ON c.id = cc.course_class_fk
JOIN `course_plan` cp ON cp.id = c.course_plan_fk
SET cc.course_plan_id = cp.id,
    cc.course_plan_name = cp.name,
    cc.course_plan_users_id = cp.users_fk,
    cc.course_plan_stage_id = cp.modality_fk,
    cc.course_plan_discipline_id = cp.discipline_fk,
    cc.class_order = c.`order`,
    cc.content = c.content,
    cc.methodology = c.methodology
WHERE cc.course_plan_id IS NULL;

INSERT IGNORE INTO `class_contents_ability` (`class_contents_fk`, `course_class_ability_fk`, `created_at`, `updated_at`)
SELECT cc.id, h.course_class_ability_fk, NOW(), NOW()
FROM `class_contents` cc
JOIN `course_class_has_class_ability` h ON h.course_class_fk = cc.course_class_fk
JOIN `course_class_abilities` a ON a.id = h.course_class_ability_fk;

-- -----------------------------------------------------------------------------
-- 4. macete_lesson_record: remove a FK para o plano e deixa lesson_plan_fk opcional
-- -----------------------------------------------------------------------------
SET @mr_fk_name := (
    SELECT CONSTRAINT_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_record'
        AND COLUMN_NAME = 'lesson_plan_fk'
        AND REFERENCED_TABLE_NAME = 'macete_lesson_plan'
    LIMIT 1
);
SET @mr_fk_sql := IF(
    @mr_fk_name IS NULL,
    'SELECT 1',
    CONCAT('ALTER TABLE `macete_lesson_record` DROP FOREIGN KEY `', @mr_fk_name, '`')
);
PREPARE mr_fk_statement FROM @mr_fk_sql;
EXECUTE mr_fk_statement;

ALTER TABLE `macete_lesson_record` MODIFY `lesson_plan_fk` INT NULL;

-- -----------------------------------------------------------------------------
-- 5. macete_lesson_record: colunas com a cópia do plano MACETE
-- -----------------------------------------------------------------------------
SET @mr_snapshot_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'macete_lesson_record'
        AND COLUMN_NAME = 'plan_name'
);
SET @mr_snapshot_sql := IF(
    @mr_snapshot_exists = 0,
    'ALTER TABLE `macete_lesson_record`
        ADD COLUMN `plan_name` VARCHAR(150) NULL AFTER `lesson_plan_fk`,
        ADD COLUMN `plan_code` VARCHAR(50) NULL AFTER `plan_name`,
        ADD COLUMN `plan_theme` VARCHAR(255) NULL AFTER `plan_code`,
        ADD COLUMN `plan_users_id` INT NULL COMMENT ''Dono do plano (sem FK)'' AFTER `plan_theme`,
        ADD COLUMN `plan_classroom_id` INT NULL COMMENT ''Turma do plano (sem FK)'' AFTER `plan_users_id`,
        ADD COLUMN `plan_stage_id` INT NULL COMMENT ''Etapa do plano (sem FK)'' AFTER `plan_classroom_id`,
        ADD COLUMN `plan_discipline_id` INT NULL COMMENT ''Componente do plano (sem FK)'' AFTER `plan_stage_id`,
        ADD COLUMN `plan_origin_template_id` INT NULL COMMENT ''Plano modelo de origem (sem FK)'' AFTER `plan_discipline_id`,
        ADD COLUMN `plan_school_year` INT NULL AFTER `plan_origin_template_id`,
        ADD COLUMN `plan_unit` VARCHAR(50) NULL AFTER `plan_school_year`,
        ADD COLUMN `plan_territory_context` TEXT NULL AFTER `plan_unit`,
        ADD COLUMN `plan_knowledge_object` TEXT NULL AFTER `plan_territory_context`,
        ADD COLUMN `plan_evaluation` TEXT NULL AFTER `plan_knowledge_object`,
        ADD COLUMN `plan_adaptations` TEXT NULL AFTER `plan_evaluation`,
        ADD COLUMN `plan_references_text` TEXT NULL AFTER `plan_adaptations`,
        ADD COLUMN `plan_stages` JSON NULL COMMENT ''Etapas e componentes do plano'' AFTER `plan_references_text`,
        ADD COLUMN `plan_sections` JSON NULL COMMENT ''Seções do plano'' AFTER `plan_stages`,
        ADD COLUMN `plan_resources` JSON NULL COMMENT ''Recursos do plano'' AFTER `plan_sections`,
        ADD COLUMN `plan_materials` JSON NULL COMMENT ''Materiais do plano'' AFTER `plan_resources`,
        ADD COLUMN `plan_abilities` JSON NULL COMMENT ''Habilidades do plano'' AFTER `plan_materials`',
    'SELECT 1'
);
PREPARE mr_snapshot_statement FROM @mr_snapshot_sql;
EXECUTE mr_snapshot_statement;

-- -----------------------------------------------------------------------------
-- 6. macete_lesson_record: preenche a cópia a partir dos planos que existem
-- -----------------------------------------------------------------------------
UPDATE `macete_lesson_record` r
JOIN `macete_lesson_plan` p ON p.id = r.lesson_plan_fk
SET r.plan_name = p.name,
    r.plan_code = p.code,
    r.plan_theme = p.theme,
    r.plan_users_id = p.users_fk,
    r.plan_classroom_id = p.classroom_fk,
    r.plan_stage_id = p.edcenso_stage_vs_modality_fk,
    r.plan_discipline_id = p.edcenso_discipline_fk,
    r.plan_origin_template_id = p.origin_template_fk,
    r.plan_school_year = p.school_year,
    r.plan_unit = p.unit,
    r.plan_territory_context = p.territory_context,
    r.plan_knowledge_object = p.knowledge_object,
    r.plan_evaluation = p.evaluation,
    r.plan_adaptations = p.adaptations,
    r.plan_references_text = p.references_text,
    r.plan_stages = COALESCE((
        SELECT JSON_ARRAYAGG(JSON_OBJECT(
            'stage_id', s.edcenso_stage_vs_modality_fk,
            'discipline_id', s.edcenso_discipline_fk
        ))
        FROM `macete_lesson_plan_stage` s WHERE s.lesson_plan_fk = p.id
    ), JSON_ARRAY()),
    r.plan_sections = COALESCE((
        SELECT JSON_ARRAYAGG(JSON_OBJECT(
            'section_type', s.section_type,
            'title', s.title,
            'target_group', s.target_group,
            'content', s.content,
            'position', s.position
        ))
        FROM `macete_lesson_plan_section` s WHERE s.lesson_plan_fk = p.id
    ), JSON_ARRAY()),
    r.plan_resources = COALESCE((
        SELECT JSON_ARRAYAGG(JSON_OBJECT(
            'resource_type', s.resource_type,
            'name', s.name,
            'amount', s.amount,
            'description', s.description
        ))
        FROM `macete_lesson_plan_resource` s WHERE s.lesson_plan_fk = p.id
    ), JSON_ARRAY()),
    r.plan_materials = COALESCE((
        SELECT JSON_ARRAYAGG(JSON_OBJECT(
            'title', s.title,
            'material_type', s.material_type,
            'description', s.description,
            'file_path', s.file_path
        ))
        FROM `macete_lesson_material` s WHERE s.lesson_plan_fk = p.id
    ), JSON_ARRAY()),
    r.plan_abilities = COALESCE((
        SELECT JSON_ARRAYAGG(JSON_OBJECT('id', a.id, 'code', a.code, 'description', a.description))
        FROM `macete_lesson_plan_ability` s
        JOIN `course_class_abilities` a ON a.id = s.ability_fk
        WHERE s.lesson_plan_fk = p.id
    ), JSON_ARRAY())
WHERE r.plan_name IS NULL;
