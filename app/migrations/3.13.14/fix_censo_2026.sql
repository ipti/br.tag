-- =============================================================================
-- TAG Migration v3.13.14 — Correções Educacenso 2026
--
-- Consolida todas as correções de layout geradas após a migration censo_2026.sql
-- Ordem obrigatória de execução (não alterar):
--
--   PARTE 1 — Registro 00: remove campo "Não possui parceria ou convênio"
--   PARTE 2 — Registro 00: campo 50 esfera reguladora + campos 51-53
--   PARTE 3 — Registro 10: língua de ensino indígena consolidada + cleanup final
--   PARTE 4 — Registro 10: corrige posição dos campos de trabalhadores
--   PARTE 5 — Registro 20: remove corders extras 15/16 e reposiciona aliases
--   PARTE 6 — Registro 20: corrige a ordem dos campos 23-27
-- =============================================================================


-- =============================================================================
-- PARTE 1 — Registro 00: remove campo "Não possui parceria ou convênio"
--
-- O layout do Censo 2026 não possui o campo "Não possui parceria ou convênio"
-- no Registro 00. O alias permaneceu na tabela com attr=NULL e corder=35,
-- deslocando todos os campos subsequentes em uma posição.
-- =============================================================================

START TRANSACTION;

-- 1. Remove o campo que não existe mais no layout 2026
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 0
  AND corder = 35
  AND attr IS NULL;

-- 2. Desloca todos os campos após o corder 35 uma posição para cima,
--    alinhando com o layout oficial do Censo 2026
UPDATE edcenso_alias
SET corder = corder - 1
WHERE `year` = 2026
  AND register = 0
  AND corder > 35;

COMMIT;


-- =============================================================================
-- PARTE 2 — Registro 00: campo 50 esfera reguladora + campos 51-53
--
-- O layout 2026 substituiu os 3 campos booleanos de esfera do órgão regulador
-- (federal/estadual/municipal) por um único campo SELECT (campo 50). Além
-- disso, os campos 51-53 (unidade vinculada, código da escola sede, código da
-- IES) não estavam presentes na tabela de alias.
--
-- DEVE ser executada após a Parte 1.
-- =============================================================================

START TRANSACTION;

-- 1. Adiciona a coluna regulation_organ_sphere à tabela de identificação
ALTER TABLE school_identification
ADD COLUMN `regulation_organ_sphere` TINYINT(1) NULL AFTER `regulation_organ_municipal`;

-- 2. Popula regulation_organ_sphere a partir dos 3 booleanos existentes
--    Mapeamento: federal=1 → 1, estadual=1 → 2, municipal=1 → 3,
--               estadual+municipal → 4, federal+estadual → 5
UPDATE school_identification
SET regulation_organ_sphere = CASE
    WHEN regulation_organ_federal = 1 AND regulation_organ_state = 1 THEN 5
    WHEN regulation_organ_state  = 1 AND regulation_organ_municipal = 1 THEN 4
    WHEN regulation_organ_federal = 1 THEN 1
    WHEN regulation_organ_state  = 1 THEN 2
    WHEN regulation_organ_municipal = 1 THEN 3
    ELSE NULL
END;

-- 3. Remove os 3 campos booleanos antigos da tabela de alias 2026
--    (também remove offer/inep/ies por segurança caso estejam presentes)
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 0
  AND attr IN (
      'regulation_organ_federal',
      'regulation_organ_state',
      'regulation_organ_municipal',
      'offer_or_linked_unity',
      'inep_head_school',
      'ies_code'
  );

-- 4. Insere os 4 novos campos nas posições corretas do layout 2026
--    Após a Parte 1 devem restar exatamente 49 campos (corders 1-49),
--    portanto os 4 novos ficam em 50-53 → total 53 campos
INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`) VALUES
(0, 50, 'regulation_organ_sphere',  'Esfera administrativa do conselho ou órgão responsável pela regulamentação/autorização', NULL, NULL, 2026),
(0, 51, 'offer_or_linked_unity',    'Unidade vinculada à escola de educação básica ou unidade ofertante de educação superior',  NULL, NULL, 2026),
(0, 52, 'inep_head_school',         'Código da Escola Sede',                                                                   NULL, NULL, 2026),
(0, 53, 'ies_code',                 'Código da IES',                                                                           NULL, NULL, 2026);

COMMIT;


-- =============================================================================
-- PARTE 3 — Registro 10: língua de ensino indígena consolidada + cleanup final
--
-- A migration censo_2026.sql inseriu 5 novos campos com shift, mas apagou
-- apenas 2 campos obsoletos. Os 3 campos extras do layout 2025 que foram
-- empurrados para além do corder 187 ficaram no banco. Somado ao
-- nativo/língua (3 linhas → 1), o total era 192 em vez de 187 no registro 10.
-- =============================================================================

START TRANSACTION;

-- 1. Adiciona coluna consolidada
ALTER TABLE `school_structure`
    ADD COLUMN `native_education_language` TINYINT NULL
        COMMENT '0=nao indigena, 1=lingua indigena, 2=lingua portuguesa, 3=ambas'
    AFTER `native_education_language_portuguese`;

-- 2. Popula a partir das três colunas booleanas existentes
UPDATE `school_structure`
SET `native_education_language` = CASE
    WHEN `native_education` IS NULL OR `native_education` = 0 THEN 0
    WHEN `native_education_language_native` = 1
     AND `native_education_language_portuguese` = 1 THEN 3
    WHEN `native_education_language_native` = 1 THEN 1
    WHEN `native_education_language_portuguese` = 1 THEN 2
    ELSE NULL
END;

-- 3. Substitui as três entradas booleanas por uma única entrada derivada
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
SET attr      = 'native_education_language',
    cdesc     = 'Lingua em que o ensino e ministrado',
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


-- =============================================================================
-- PARTE 4 — Registro 10: corrige posição dos campos de trabalhadores
--
-- A migration censo_2026.sql inseriu workers_social_worker no corder 120
-- (correto), mas não removeu internet_access_local_inexistet que permaneceu
-- no corder 119. O resultado foi workers_garden_planting_agricultural no 121
-- e workers_inexistente/nenhum no 138 — ambos com offset de +1.
-- =============================================================================

START TRANSACTION;

-- 1. Remove o campo extra (coberto pelo valor 0 do corder 118 consolidado)
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 10
  AND attr = 'internet_access_local_inexistet';

-- 2. Move workers_garden_planting_agricultural para o corder correto (119)
UPDATE edcenso_alias
SET corder = 119
WHERE `year` = 2026
  AND register = 10
  AND attr = 'workers_garden_planting_agricultural';

-- 3. Fecha o gap no corder 121 deslocando os campos seguintes
UPDATE edcenso_alias
SET corder = corder - 1
WHERE `year` = 2026
  AND register = 10
  AND corder >= 122;

COMMIT;


-- =============================================================================
-- PARTE 5 — Registro 20: remove corders extras 15/16 e reposiciona aliases
--
-- O layout INEP 2026 não possui campos independentes de flag "Atividade
-- complementar" e "AEE" nas posições 15 e 16. Os corders 15 e 16 criados no
-- banco são extras e deslocam todos os campos seguintes em +2 em relação ao
-- spec.
-- =============================================================================

-- 1. Remove os corders extras (não existem no layout INEP 2026)
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 20
  AND corder IN (15, 16);

-- 2. Reposiciona todos os campos que estavam deslocados
UPDATE edcenso_alias
SET corder = corder - 2
WHERE `year` = 2026
  AND register = 20
  AND corder >= 17;

-- 3. Campo 22 (Turma de Educação Especial / classe especial): associa ao
--    atributo correto do modelo Classroom
UPDATE edcenso_alias
SET attr = 'is_special_education'
WHERE `year` = 2026
  AND register = 20
  AND corder = 22;


-- =============================================================================
-- PARTE 6 — Registro 20: corrige a ordem dos campos 23-27
--
-- Após o shift da Parte 5, os campos ficaram fora de ordem. Este bloco
-- corrige os corders 23-27 para:
--   23 = Etapa agregada
--   24 = Etapa
--   25 = Código do eixo do curso de qualificação profissional
--   26 = Código do curso
--   27 = Carga horária total do curso
-- =============================================================================

-- Passo 1: Move para posições temporárias para evitar conflito de corder
UPDATE edcenso_alias SET corder = 1023 WHERE `year` = 2026 AND register = 20 AND corder = 23;
UPDATE edcenso_alias SET corder = 1024 WHERE `year` = 2026 AND register = 20 AND corder = 24;
UPDATE edcenso_alias SET corder = 1025 WHERE `year` = 2026 AND register = 20 AND corder = 25;
UPDATE edcenso_alias SET corder = 1026 WHERE `year` = 2026 AND register = 20 AND corder = 26;
UPDATE edcenso_alias SET corder = 1027 WHERE `year` = 2026 AND register = 20 AND corder = 27;

-- Passo 2: Posiciona cada campo no corder correto
-- 1024 era etapa_agregada (estava em 24) → vai para 23
UPDATE edcenso_alias SET corder = 23 WHERE `year` = 2026 AND register = 20 AND corder = 1024;
-- 1026 era etapa (estava em 26) → vai para 24
UPDATE edcenso_alias SET corder = 24 WHERE `year` = 2026 AND register = 20 AND corder = 1026;
-- 1023 era qualification_course_axis (estava em 23) → vai para 25
UPDATE edcenso_alias SET corder = 25 WHERE `year` = 2026 AND register = 20 AND corder = 1023;
-- 1027 era codigo_curso (estava em 27) → vai para 26
UPDATE edcenso_alias SET corder = 26 WHERE `year` = 2026 AND register = 20 AND corder = 1027;
-- 1025 era total_course_hours (estava em 25) → vai para 27
UPDATE edcenso_alias SET corder = 27 WHERE `year` = 2026 AND register = 20 AND corder = 1025;
