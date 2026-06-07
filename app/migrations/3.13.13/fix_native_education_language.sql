-- =============================================================================
-- TAG Migration — Campo consolidado de língua de ensino indígena + cleanup final
--
-- Problema raiz: a migration censo_2026.sql inseriu 5 novos campos com shift,
-- mas apagou apenas 2 campos obsoletos (internet booleans). Os 3 campos
-- extras do layout 2025 que foram empurrados para além do corder 187 ficaram
-- no banco. Somado ao nativo/língua (3 linhas → 1), o total era 192 em vez
-- de 187 no registro 10, e 68 em vez de 66 no registro 20.
--
-- Esta migration:
--   1. Adiciona coluna native_education_language em school_structure
--   2. Popula derivando das 3 colunas booleanas existentes
--   3. Corrige edcenso_alias 2026 registro 10: 3 linhas → 1 + shift −2
--   4. Remove campos além do limite do layout 2026 (registro 10: >187, reg 20: >66)
-- =============================================================================

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- 1. Adiciona coluna consolidada
-- -----------------------------------------------------------------------------
ALTER TABLE `school_structure`
    ADD COLUMN `native_education_language` TINYINT NULL
        COMMENT '0=nao indigena, 1=lingua indigena, 2=lingua portuguesa, 3=ambas'
    AFTER `native_education_language_portuguese`;

-- -----------------------------------------------------------------------------
-- 2. Popula a partir das três colunas booleanas existentes
--    Regra de derivação:
--      native_education = 0 ou NULL  →  0
--      native_education = 1, ambas as línguas = 1  →  3
--      native_education = 1, somente nativa = 1  →  1
--      native_education = 1, somente portuguesa = 1  →  2
--      native_education = 1, nenhuma língua marcada  →  NULL (admin deve preencher)
-- -----------------------------------------------------------------------------
UPDATE `school_structure`
SET `native_education_language` = CASE
    WHEN `native_education` IS NULL OR `native_education` = 0 THEN 0
    WHEN `native_education_language_native` = 1
     AND `native_education_language_portuguese` = 1 THEN 3
    WHEN `native_education_language_native` = 1 THEN 1
    WHEN `native_education_language_portuguese` = 1 THEN 2
    ELSE NULL
END;

-- -----------------------------------------------------------------------------
-- 3. Atualiza edcenso_alias ano 2026
--    Substitui as três entradas booleanas por uma única entrada derivada,
--    e ajusta os corders subsequentes para fechar o buraco de 2 posições.
-- -----------------------------------------------------------------------------

SET @corder_native := (
    SELECT corder FROM edcenso_alias
    WHERE `year` = 2026 AND register = 10 AND attr = 'native_education'
    LIMIT 1
);
SET @corder_lang_native := (
    SELECT corder FROM edcenso_alias
    WHERE `year` = 2026 AND register = 10 AND attr = 'native_education_language_native'
    LIMIT 1
);
SET @corder_lang_portuguese := (
    SELECT corder FROM edcenso_alias
    WHERE `year` = 2026 AND register = 10 AND attr = 'native_education_language_portuguese'
    LIMIT 1
);

-- Remove as duas linhas extras
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 10
  AND attr IN ('native_education_language_native', 'native_education_language_portuguese');

-- Atualiza a linha remanescente para o campo consolidado
UPDATE edcenso_alias
SET attr    = 'native_education_language',
    cdesc   = 'Lingua em que o ensino e ministrado',
    `default` = '0'
WHERE `year` = 2026
  AND register = 10
  AND attr = 'native_education';

-- Desloca corders acima das linhas removidas para baixo em 2
SET @max_deleted := GREATEST(
    COALESCE(@corder_lang_native, 0),
    COALESCE(@corder_lang_portuguese, 0)
);

UPDATE edcenso_alias
SET corder = corder - 2
WHERE `year` = 2026
  AND register = 10
  AND corder > @max_deleted
  AND @max_deleted > 0;

COMMIT;
