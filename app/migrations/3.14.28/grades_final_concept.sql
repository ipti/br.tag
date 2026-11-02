-- =============================================================================
-- TAG Migration v3.14.28 - Conceito final nas estruturas por conceito
-- =============================================================================
-- A partir de 2026 a ata de notas de turma por conceito mostra apenas o
-- conceito final lancado na tela de notas e deixa de converter a media dos
-- valores dos conceitos em conceito.
--
-- 1. Cria a unidade CONCEITO FINAL (e sua modalidade) nas estruturas por
--    conceito que ainda nao tem. Estruturas novas ja nascem com ela.
-- 2. Nas turmas anteriores a 2026 sem conceito final, grava como conceito
--    final o conceito que a ata mostrava pela regra antiga: o conceito de
--    maior valor que seja menor ou igual a maior nota entre final_media e
--    rec_final, considerando so linhas em que a ata exibia conceito
--    (grade_concept_1 preenchido). Quando a ata nao encontrava conceito,
--    nada e gravado. Se ja existe conceito final lancado na tela de notas,
--    ele e mantido e so copiado para o grade_results.
--    O conceito final fica no grade (unidade CONCEITO FINAL), que a tela de
--    notas mostra e o recalculo le, e no grade_results.final_concept, que os
--    relatorios leem.
--
-- Idempotente: so cria o que falta e so preenche final_concept vazio.

-- 1. Unidade e modalidade de conceito final
INSERT INTO grade_unity (name, type, grade_calculation_fk, grade_rules_fk, created_at, updated_at)
SELECT 'CONCEITO FINAL', 'FC', 2, gr.id, NOW(), NOW()
FROM grade_rules gr
WHERE gr.rule_type = 'C'
  AND NOT EXISTS (
      SELECT 1 FROM grade_unity gu
      WHERE gu.grade_rules_fk = gr.id AND gu.type = 'FC'
  );

INSERT INTO grade_unity_modality (name, type, weight, grade_unity_fk, created_at, updated_at)
SELECT _utf8mb4'AVALIAÇÃO', 'FC', NULL, gu.id, NOW(), NOW()
FROM grade_unity gu
WHERE gu.type = 'FC'
  AND NOT EXISTS (
      SELECT 1 FROM grade_unity_modality gum
      WHERE gum.grade_unity_fk = gu.id AND gum.type = 'FC'
  );

-- 2. Conceito final das turmas anteriores a 2026
DROP TEMPORARY TABLE IF EXISTS tmp_final_concept;

CREATE TEMPORARY TABLE tmp_final_concept AS
SELECT
    x.grade_results_id,
    x.enrollment_fk,
    x.discipline_fk,
    x.modality_fk,
    COALESCE(
        (SELECT g.grade_concept_fk FROM grade g
         WHERE g.enrollment_fk = x.enrollment_fk
           AND g.discipline_fk = x.discipline_fk
           AND g.grade_unity_modality_fk = x.modality_fk
           AND g.grade_concept_fk IS NOT NULL
         ORDER BY g.id DESC LIMIT 1),
        x.ata_concept_fk
    ) AS concept_fk
FROM (
    SELECT
        res.id AS grade_results_id,
        res.enrollment_fk,
        res.discipline_fk,
        -- estrutura por conceito da turma, de preferencia a da etapa do aluno
        (SELECT gum.id
         FROM classroom_vs_grade_rules cvgr
         JOIN grade_rules r ON r.id = cvgr.grade_rules_fk AND r.rule_type = 'C'
         JOIN grade_unity gu ON gu.grade_rules_fk = r.id AND gu.type = 'FC'
         JOIN grade_unity_modality gum ON gum.grade_unity_fk = gu.id AND gum.type = 'FC'
         LEFT JOIN grade_rules_vs_edcenso_stage_vs_modality grv ON grv.grade_rules_fk = r.id
             AND grv.edcenso_stage_vs_modality_fk = COALESCE(se.edcenso_stage_vs_modality_fk, c.edcenso_stage_vs_modality_fk)
         WHERE cvgr.classroom_fk = c.id
         ORDER BY grv.grade_rules_fk IS NULL, r.id, gum.id
         LIMIT 1) AS modality_fk,
        -- conceito que a ata mostrava pela media (empate de valor: menor id)
        (SELECT gc.id
         FROM grade_concept gc
         WHERE gc.value IS NOT NULL
           AND (res.final_media IS NOT NULL OR res.rec_final IS NOT NULL)
           AND gc.value <= GREATEST(IFNULL(res.final_media, 0), IFNULL(res.rec_final, 0))
         ORDER BY gc.value DESC, gc.id
         LIMIT 1) AS ata_concept_fk
    FROM grade_results res
    JOIN student_enrollment se ON se.id = res.enrollment_fk
    JOIN classroom c ON c.id = se.classroom_fk
    WHERE c.school_year < 2026
      AND res.final_concept IS NULL
      AND res.grade_concept_1 IS NOT NULL
      AND res.grade_concept_1 <> ''
) x
WHERE x.modality_fk IS NOT NULL;

DELETE FROM tmp_final_concept WHERE concept_fk IS NULL;

UPDATE grade g
JOIN tmp_final_concept t
  ON t.enrollment_fk = g.enrollment_fk
 AND t.discipline_fk = g.discipline_fk
 AND t.modality_fk = g.grade_unity_modality_fk
SET g.grade_concept_fk = t.concept_fk, g.updated_at = NOW()
WHERE g.grade_concept_fk IS NULL;

INSERT INTO grade (grade, grade_concept_fk, enrollment_fk, discipline_fk, grade_unity_modality_fk, created_at, updated_at)
SELECT NULL, t.concept_fk, t.enrollment_fk, t.discipline_fk, t.modality_fk, NOW(), NOW()
FROM tmp_final_concept t
WHERE NOT EXISTS (
    SELECT 1 FROM grade g
    WHERE g.enrollment_fk = t.enrollment_fk
      AND g.discipline_fk = t.discipline_fk
      AND g.grade_unity_modality_fk = t.modality_fk
);

UPDATE grade_results res
JOIN tmp_final_concept t ON t.grade_results_id = res.id
SET res.final_concept = t.concept_fk;

DROP TEMPORARY TABLE IF EXISTS tmp_final_concept;
