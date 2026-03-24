-- =============================================================================
-- Script de Deduplicação de Professores (Instructor)
-- =============================================================================
-- ESTRATÉGIA:
--   - Mantém o registro mais ANTIGO (menor id) como definitivo (keeper)
--   - Mescla dados do(s) registro(s) mais novo(s) no keeper:
--     apenas campos NULL/vazios no keeper são preenchidos com valores do duplicado
--   - Redireciona todos os vínculos de turma, faults, schedule, class_board, etc.
--   - Remove os registros duplicados e seus dados relacionados
--
-- !! FAÇA BACKUP ANTES DE EXECUTAR !!
-- =============================================================================

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- PASSO 1: Identificar os grupos de CPF duplicados
-- -----------------------------------------------------------------------------
DROP TEMPORARY TABLE IF EXISTS tmp_dup_groups;
CREATE TEMPORARY TABLE tmp_dup_groups AS
SELECT
    da.cpf,
    MIN(ii.id) AS keeper_id
FROM instructor_documents_and_address da
JOIN instructor_identification ii ON ii.id = da.id
WHERE da.cpf IS NOT NULL
  AND TRIM(da.cpf) != ''
GROUP BY da.cpf
HAVING COUNT(*) > 1;

-- Checar quantos grupos de duplicatas foram encontrados
SELECT CONCAT('Grupos de CPF duplicados encontrados: ', COUNT(*)) AS info
FROM tmp_dup_groups;

-- -----------------------------------------------------------------------------
-- PASSO 2: Identificar todos os registros duplicados (não-keepers)
-- -----------------------------------------------------------------------------
DROP TEMPORARY TABLE IF EXISTS tmp_duplicates;
CREATE TEMPORARY TABLE tmp_duplicates AS
SELECT
    g.cpf,
    g.keeper_id,
    ii.id AS dup_id,
    ii.name AS dup_name,
    ii2.name AS keeper_name
FROM tmp_dup_groups g
JOIN instructor_documents_and_address da ON da.cpf = g.cpf
JOIN instructor_identification ii ON ii.id = da.id AND ii.id != g.keeper_id
JOIN instructor_identification ii2 ON ii2.id = g.keeper_id;

-- Exibir preview das duplicatas que serão resolvidas
SELECT
    cpf,
    keeper_id,
    keeper_name,
    dup_id,
    dup_name
FROM tmp_duplicates
ORDER BY cpf, dup_id;

-- -----------------------------------------------------------------------------
-- PASSO 3: Mesclar dados de instructor_identification
--          (preenche campos NULL do keeper com valores do duplicado)
-- -----------------------------------------------------------------------------
UPDATE instructor_identification keeper
JOIN tmp_duplicates d ON keeper.id = d.keeper_id
JOIN instructor_identification dup ON dup.id = d.dup_id
SET
    keeper.civil_name          = COALESCE(NULLIF(TRIM(keeper.civil_name), ''), dup.civil_name),
    keeper.email               = COALESCE(NULLIF(TRIM(keeper.email), ''), dup.email),
    keeper.nis                 = COALESCE(NULLIF(TRIM(keeper.nis), ''), dup.nis),
    keeper.inep_id             = COALESCE(NULLIF(TRIM(keeper.inep_id), ''), dup.inep_id),
    keeper.filiation_1         = COALESCE(NULLIF(TRIM(keeper.filiation_1), ''), dup.filiation_1),
    keeper.filiation_2         = COALESCE(NULLIF(TRIM(keeper.filiation_2), ''), dup.filiation_2),
    keeper.edcenso_uf_fk       = COALESCE(NULLIF(keeper.edcenso_uf_fk, 0), NULLIF(dup.edcenso_uf_fk, 0)),
    keeper.edcenso_city_fk     = COALESCE(NULLIF(keeper.edcenso_city_fk, 0), NULLIF(dup.edcenso_city_fk, 0)),
    keeper.edcenso_nation_fk   = COALESCE(NULLIF(keeper.edcenso_nation_fk, 0), NULLIF(dup.edcenso_nation_fk, 0)),
    keeper.id_indigenous_people = COALESCE(NULLIF(TRIM(keeper.id_indigenous_people), ''), dup.id_indigenous_people);

-- -----------------------------------------------------------------------------
-- PASSO 4: Mesclar dados de instructor_documents_and_address
-- -----------------------------------------------------------------------------
UPDATE instructor_documents_and_address keeper_da
JOIN tmp_duplicates d ON keeper_da.id = d.keeper_id
JOIN instructor_documents_and_address dup_da ON dup_da.id = d.dup_id
SET
    keeper_da.area_of_residence = COALESCE(NULLIF(keeper_da.area_of_residence, 0), NULLIF(dup_da.area_of_residence, 0)),
    keeper_da.diff_location     = COALESCE(NULLIF(keeper_da.diff_location, 0), NULLIF(dup_da.diff_location, 0)),
    keeper_da.cep               = COALESCE(NULLIF(TRIM(keeper_da.cep), ''), dup_da.cep),
    keeper_da.address           = COALESCE(NULLIF(TRIM(keeper_da.address), ''), dup_da.address),
    keeper_da.address_number    = COALESCE(NULLIF(TRIM(keeper_da.address_number), ''), dup_da.address_number),
    keeper_da.complement        = COALESCE(NULLIF(TRIM(keeper_da.complement), ''), dup_da.complement),
    keeper_da.neighborhood      = COALESCE(NULLIF(TRIM(keeper_da.neighborhood), ''), dup_da.neighborhood),
    keeper_da.edcenso_uf_fk     = COALESCE(NULLIF(keeper_da.edcenso_uf_fk, 0), NULLIF(dup_da.edcenso_uf_fk, 0)),
    keeper_da.edcenso_city_fk   = COALESCE(NULLIF(keeper_da.edcenso_city_fk, 0), NULLIF(dup_da.edcenso_city_fk, 0));

-- -----------------------------------------------------------------------------
-- PASSO 5: Mesclar dados de instructor_variable_data
-- -----------------------------------------------------------------------------
UPDATE instructor_variable_data keeper_vd
JOIN tmp_duplicates d ON keeper_vd.id = d.keeper_id
JOIN instructor_variable_data dup_vd ON dup_vd.id = d.dup_id
SET
    keeper_vd.scholarity                          = COALESCE(NULLIF(keeper_vd.scholarity, 0), NULLIF(dup_vd.scholarity, 0)),
    keeper_vd.high_education_situation_1          = COALESCE(NULLIF(keeper_vd.high_education_situation_1, 0), NULLIF(dup_vd.high_education_situation_1, 0)),
    keeper_vd.high_education_course_code_1_fk     = COALESCE(keeper_vd.high_education_course_code_1_fk, dup_vd.high_education_course_code_1_fk),
    keeper_vd.high_education_institution_code_1_fk = COALESCE(keeper_vd.high_education_institution_code_1_fk, dup_vd.high_education_institution_code_1_fk),
    keeper_vd.high_education_situation_2          = COALESCE(NULLIF(keeper_vd.high_education_situation_2, 0), NULLIF(dup_vd.high_education_situation_2, 0)),
    keeper_vd.high_education_course_code_2_fk     = COALESCE(keeper_vd.high_education_course_code_2_fk, dup_vd.high_education_course_code_2_fk),
    keeper_vd.high_education_institution_code_2_fk = COALESCE(keeper_vd.high_education_institution_code_2_fk, dup_vd.high_education_institution_code_2_fk),
    keeper_vd.high_education_situation_3          = COALESCE(NULLIF(keeper_vd.high_education_situation_3, 0), NULLIF(dup_vd.high_education_situation_3, 0)),
    keeper_vd.high_education_course_code_3_fk     = COALESCE(keeper_vd.high_education_course_code_3_fk, dup_vd.high_education_course_code_3_fk),
    keeper_vd.high_education_institution_code_3_fk = COALESCE(keeper_vd.high_education_institution_code_3_fk, dup_vd.high_education_institution_code_3_fk);

-- -----------------------------------------------------------------------------
-- PASSO 6: Redirecionar vínculos de turma (instructor_teaching_data)
--          Evita duplicar vínculos já existentes para o keeper na mesma turma/ano
-- -----------------------------------------------------------------------------
UPDATE instructor_teaching_data itd
JOIN tmp_duplicates d ON itd.instructor_fk = d.dup_id
-- Só atualiza se o keeper ainda não tem vínculo com essa turma
LEFT JOIN instructor_teaching_data existing
    ON existing.instructor_fk = d.keeper_id
   AND existing.classroom_id_fk = itd.classroom_id_fk
SET itd.instructor_fk = d.keeper_id
WHERE existing.id IS NULL;

-- Deleta vínculos restantes do duplicado (onde o keeper já tinha o mesmo vínculo)
DELETE itd
FROM instructor_teaching_data itd
JOIN tmp_duplicates d ON itd.instructor_fk = d.dup_id;

-- -----------------------------------------------------------------------------
-- PASSO 7: Redirecionar faltas (instructor_faults)
-- -----------------------------------------------------------------------------
UPDATE instructor_faults f
JOIN tmp_duplicates d ON f.instructor_fk = d.dup_id
SET f.instructor_fk = d.keeper_id;

-- -----------------------------------------------------------------------------
-- PASSO 8: Redirecionar schedule
-- -----------------------------------------------------------------------------
UPDATE schedule s
JOIN tmp_duplicates d ON s.instructor_fk = d.dup_id
SET s.instructor_fk = d.keeper_id;

-- -----------------------------------------------------------------------------
-- PASSO 9: Redirecionar class_board
-- -----------------------------------------------------------------------------
UPDATE class_board cb
JOIN tmp_duplicates d ON cb.instructor_fk = d.dup_id
SET cb.instructor_fk = d.keeper_id;

-- -----------------------------------------------------------------------------
-- PASSO 10: Redirecionar substitute_instructor
-- -----------------------------------------------------------------------------
UPDATE substitute_instructor si
JOIN tmp_duplicates d ON si.instructor_fk = d.dup_id
SET si.instructor_fk = d.keeper_id;

-- -----------------------------------------------------------------------------
-- PASSO 11: Deletar dados auxiliares dos duplicados
-- -----------------------------------------------------------------------------
DELETE FROM instructor_documents_and_address
WHERE id IN (SELECT dup_id FROM tmp_duplicates);

DELETE FROM instructor_variable_data
WHERE id IN (SELECT dup_id FROM tmp_duplicates);

-- -----------------------------------------------------------------------------
-- PASSO 12: Deletar o usuário do sistema vinculado ao duplicado
--           (apenas se for diferente do keeper; o keeper mantém seu usuário)
-- -----------------------------------------------------------------------------
-- Desvincula o users_fk antes de deletar o instructor
UPDATE instructor_identification dup
JOIN tmp_duplicates d ON dup.id = d.dup_id
SET dup.users_fk = NULL;

-- Aqui NÂOO deletamos o users automaticamente pois pode haver outros vínculos
-- Se desejar deletar os users orfãos, rode após conferir:
-- DELETE u FROM users u LEFT JOIN instructor_identification ii ON ii.users_fk = u.id
-- LEFT JOIN users_school us ON us.user_fk = u.id WHERE ii.id IS NULL AND us.id IS NULL;

-- -----------------------------------------------------------------------------
-- PASSO 13: Deletar os registros duplicados de instructor_identification
-- -----------------------------------------------------------------------------
DELETE FROM instructor_identification
WHERE id IN (SELECT dup_id FROM tmp_duplicates);

-- -----------------------------------------------------------------------------
-- PASSO 14: Exibir resultado final
-- -----------------------------------------------------------------------------
SELECT
    CONCAT('Duplicatas resolvidas: ', COUNT(*)) AS resultado
FROM tmp_duplicates;

-- -----------------------------------------------------------------------------
-- CONFIRMAR ou REVERTER:
--   - Analise o resultado do SELECT acima e os dados da tabela antes de COMMIT
--   - Se tudo estiver correto, troque ROLLBACK por COMMIT
-- -----------------------------------------------------------------------------
ROLLBACK;
-- COMMIT;
