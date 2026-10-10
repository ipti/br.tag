-- =============================================================================
-- TAG Migration v3.14.25 - MACETE lesson plan: campo único de Adaptações (TCDA-1263)
-- =============================================================================
-- A etapa "Complementar" do Plano MACETE tinha "Adaptações" dividido em 4
-- subcampos (Crianças neurodivergentes, Recomposição de aprendizagem, Turma
-- multisseriada, Caso falte material), cada um gravado como uma linha em
-- macete_lesson_plan_section/macete_lesson_plan_template_section. A pedido,
-- isso vira um único campo de texto, no mesmo padrão de Avaliação/Referências
-- (coluna de texto direto em macete_lesson_plan/macete_lesson_plan_template).
--
-- O módulo MACETE ainda está em fase de teste/piloto, sem dados reais em
-- produção, então não há necessidade de backfill: só adiciona a coluna nova
-- (idempotente) e remove as linhas de teste das 4 subdivisões antigas, que
-- deixam de ser gravadas pela tela a partir de agora.

-- -----------------------------------------------------------------------------
-- 1a. macete_lesson_plan.adaptations
-- -----------------------------------------------------------------------------
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
    'SELECT 1'
);
PREPARE macete_plan_add_adaptations_statement FROM @macete_lesson_plan_add_adaptations_sql;
EXECUTE macete_plan_add_adaptations_statement;

-- -----------------------------------------------------------------------------
-- 1b. macete_lesson_plan_template.adaptations
-- -----------------------------------------------------------------------------
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
    'SELECT 1'
);
PREPARE macete_template_add_adaptations_statement FROM @macete_lesson_plan_template_add_adaptations_sql;
EXECUTE macete_template_add_adaptations_statement;

-- -----------------------------------------------------------------------------
-- 2. Limpeza das linhas de teste das 4 subdivisões antigas de Adaptações
-- -----------------------------------------------------------------------------
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

-- No DEALLOCATE PREPARE: MySQL libera automaticamente ao fim da sessão, e
-- liberar explicitamente aqui falha com "Unknown prepared statement handler"
-- em ferramentas/pools que não garantem que PREPARE e EXECUTE rodem na mesma
-- conexão (mesmo motivo documentado na migration 3.14.24 que removeu
-- macete_lesson_record.status).
