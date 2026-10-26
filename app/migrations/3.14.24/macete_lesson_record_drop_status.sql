-- =============================================================================
-- TAG Migration v3.14.24 - MACETE lesson record: remove status (TCDA-1254)
-- =============================================================================
-- macete_lesson_record.status (Rascunho/Concluído) era só um rótulo manual
-- sem nenhum efeito no sistema: nada filtrava, bloqueava edição ou mudava a
-- contagem de aulas registradas/pendentes a partir dele. Removido a pedido,
-- mesmo raciocínio já aplicado a macete_lesson_plan.status. Idempotente:
-- seguro rodar em bases onde a coluna já não existe.

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
    'SELECT 1'
);
PREPARE macete_record_drop_status_statement FROM @macete_lesson_record_drop_status_sql;
EXECUTE macete_record_drop_status_statement;
-- No DEALLOCATE PREPARE: MySQL releases it automatically when the session ends,
-- and explicitly deallocating here fails with "Unknown prepared statement handler"
-- on tools/pools that don't guarantee PREPARE and this statement run on the same
-- connection (observed when running this script manually against production).
