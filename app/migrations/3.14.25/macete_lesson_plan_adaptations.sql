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
-- Esta migration:
-- 1. Adiciona a coluna `adaptations` nas duas tabelas (idempotente).
-- 2. Faz backfill do conteúdo já cadastrado nas 4 subdivisões antigas,
--    concatenando com o título de cada uma, preservando o histórico já
--    preenchido por escolas/professores antes da tela parar de gravar nelas.
--    Só preenche quando `adaptations` ainda está vazio, então é seguro rodar
--    mais de uma vez.
--
-- As linhas antigas em macete_lesson_plan_section/macete_lesson_plan_template_section
-- não são apagadas aqui; elas deixam de ser recriadas a partir do próximo save
-- de cada plano (a tela não envia mais esses 4 tipos), já com o conteúdo
-- correspondente preservado na nova coluna.

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
-- 2a. Backfill de macete_lesson_plan.adaptations a partir das 4 seções antigas
-- -----------------------------------------------------------------------------
UPDATE macete_lesson_plan plan
JOIN (
    SELECT
        s.lesson_plan_fk,
        GROUP_CONCAT(
            CONCAT('<p><strong>', s.title, '</strong></p>', s.content)
            ORDER BY FIELD(
                s.section_type,
                'ADAPTATION_NEURODIVERGENT',
                'ADAPTATION_RECOVERY',
                'ADAPTATION_MULTIGRADE',
                'ADAPTATION_MISSING_MATERIAL'
            )
            SEPARATOR ''
        ) AS merged_content
    FROM macete_lesson_plan_section s
    WHERE s.section_type IN (
        'ADAPTATION_NEURODIVERGENT',
        'ADAPTATION_RECOVERY',
        'ADAPTATION_MULTIGRADE',
        'ADAPTATION_MISSING_MATERIAL'
    )
        AND s.content IS NOT NULL
        AND s.content <> ''
    GROUP BY s.lesson_plan_fk
) merged ON merged.lesson_plan_fk = plan.id
SET plan.adaptations = merged.merged_content
WHERE plan.adaptations IS NULL OR plan.adaptations = '';

-- -----------------------------------------------------------------------------
-- 2b. Backfill de macete_lesson_plan_template.adaptations (mesma lógica)
-- -----------------------------------------------------------------------------
UPDATE macete_lesson_plan_template tpl
JOIN (
    SELECT
        s.lesson_plan_template_fk,
        GROUP_CONCAT(
            CONCAT('<p><strong>', s.title, '</strong></p>', s.content)
            ORDER BY FIELD(
                s.section_type,
                'ADAPTATION_NEURODIVERGENT',
                'ADAPTATION_RECOVERY',
                'ADAPTATION_MULTIGRADE',
                'ADAPTATION_MISSING_MATERIAL'
            )
            SEPARATOR ''
        ) AS merged_content
    FROM macete_lesson_plan_template_section s
    WHERE s.section_type IN (
        'ADAPTATION_NEURODIVERGENT',
        'ADAPTATION_RECOVERY',
        'ADAPTATION_MULTIGRADE',
        'ADAPTATION_MISSING_MATERIAL'
    )
        AND s.content IS NOT NULL
        AND s.content <> ''
    GROUP BY s.lesson_plan_template_fk
) merged ON merged.lesson_plan_template_fk = tpl.id
SET tpl.adaptations = merged.merged_content
WHERE tpl.adaptations IS NULL OR tpl.adaptations = '';

-- No DEALLOCATE PREPARE: MySQL libera automaticamente ao fim da sessão, e
-- liberar explicitamente aqui falha com "Unknown prepared statement handler"
-- em ferramentas/pools que não garantem que PREPARE e EXECUTE rodem na mesma
-- conexão (mesmo motivo documentado na migration 3.14.24 que removeu
-- macete_lesson_record.status).
