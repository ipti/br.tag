-- =============================================================================
-- TAG Migration v3.14.23 - Contextualização por território na Escola (TCDA-1248)
-- =============================================================================
-- A contextualização por território deixa de ser um campo do Plano de Aula
-- MACETE e passa a ser um campo opcional fixo vinculado à própria escola,
-- funcionando como um "subtítulo" do nome da escola. O campo só é exibido no
-- formulário de escola quando a feature do MACETE (Plano de Aula ou Aulas
-- Ministradas) está habilitada para o município.
-- Idempotente: seguro rodar em instalações onde a coluna já existe.

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
    'SELECT 1'
);
PREPARE school_territory_context_statement FROM @school_territory_context_sql;
EXECUTE school_territory_context_statement;
-- No DEALLOCATE PREPARE: MySQL releases it automatically when the session ends,
-- and explicitly deallocating here fails with "Unknown prepared statement handler"
-- on tools/pools that don't guarantee PREPARE and this statement run on the same
-- connection (observed when running this script manually against production).
