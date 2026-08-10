-- =============================================================================
-- TAG Migration v3.14.22 - MACETE lesson plan code
-- =============================================================================
-- Adds an alphanumeric code to macete_lesson_plan so a plan/template can be
-- searched by code from the listing screen (same idea as the BNCC code search).
-- Idempotent: safe to run on installations where the column already exists.

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
    'SELECT 1'
);
PREPARE macete_lesson_plan_code_statement FROM @macete_lesson_plan_code_sql;
EXECUTE macete_lesson_plan_code_statement;
-- No DEALLOCATE PREPARE: MySQL releases it automatically when the session ends,
-- and explicitly deallocating here fails with "Unknown prepared statement handler"
-- on tools/pools that don't guarantee PREPARE and this statement run on the same
-- connection (observed when running this script manually against production).
