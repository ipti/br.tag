-- TAG Migration v3.13.21 -- Unificacao de frequencia controlada por municipio
--
-- Ate agora, a "frequencia unificada/polivalente" (turma com um unico
-- registro de aula por dia, sem separacao por disciplina/componente
-- curricular) era decidida por duas fontes ao mesmo tempo:
--   1) a coluna edcenso_stage_vs_modality.unified_frequency;
--   2) uma lista fixa de codigos de etapa no codigo da aplicacao
--      (TagUtils::isStageMinorEducation), usada como fallback sempre que
--      a coluna estivesse zerada.
--
-- O codigo da aplicacao foi alterado para usar exclusivamente a coluna
-- unified_frequency, dando ao municipio controle total sobre a unificacao
-- de frequencia por etapa (tela de configuracao de etapa, em
-- app/modules/stages).
--
-- Este script marca unified_frequency = 1 para as etapas que ja se
-- comportavam como unificadas por causa da lista fixa antiga (Educacao
-- Infantil e Ensino Fundamental de 9 anos - 1o ao 5o ano), preservando o
-- comportamento atual por padrao para quem nao mexer na configuracao.
-- Municipios que quiserem desunificar alguma dessas etapas podem desmarcar
-- o campo na tela de configuracao de etapa.

START TRANSACTION;

UPDATE edcenso_stage_vs_modality
    SET unified_frequency = 1
    WHERE edcenso_associated_stage_id IN (1, 2, 3, 4, 5, 6, 7, 8, 14, 15, 16, 17, 18);

COMMIT;
