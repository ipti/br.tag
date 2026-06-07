-- =============================================================================
-- TAG Migration — Registro 10: corrige posição dos campos de trabalhadores
--
-- Problema: a migration censo_2026.sql inseriu workers_social_worker no
-- corder 120 (correto), mas não removeu internet_access_local_inexistet
-- que permaneceu no corder 119. No layout 2026, esse campo deixou de
-- existir como campo separado — o corder 118 (Rede local consolidado) já
-- cobre o valor 0 = "Não há rede local". O resultado foi:
--   - corder 119: internet_access_local_inexistet (campo EXTRA)
--   - corder 121: workers_garden_planting_agricultural (deveria ser 119)
--   - corder 138: workers_inexistente / nenhum (deveria ser 137)
-- =============================================================================

START TRANSACTION;

-- 1. Remove o campo extra (coberto pelo valor 0 do corder 118 consolidado)
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 10
  AND attr = 'internet_access_local_inexistet';

-- 2. Move workers_garden_planting_agricultural do corder 121 para o 119
--    (corder 119 foi liberado pela deleção acima)
UPDATE edcenso_alias
SET corder = 119
WHERE `year` = 2026
  AND register = 10
  AND attr = 'workers_garden_planting_agricultural';

-- 3. Desloca os campos a partir do corder 122 uma posição para baixo,
--    fechando o gap que ficou no 121 após o workers_garden sair de lá
UPDATE edcenso_alias
SET corder = corder - 1
WHERE `year` = 2026
  AND register = 10
  AND corder >= 122;

COMMIT;
