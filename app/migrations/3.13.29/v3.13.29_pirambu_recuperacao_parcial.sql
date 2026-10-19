-- =============================================================================
-- TAG Migration v3.13.29 - Pirambu: corrige recuperação parcial cadastrada como
-- unidade comum (TCDA-1242)
-- =============================================================================
-- CORREÇÃO PONTUAL DE DADOS — RODAR APENAS EM pirambu.tag.ong.br
-- Se executado via `composer run migrate`, use OBRIGATORIAMENTE
-- --db-filter=pirambu (o runner por padrão roda em TODAS as bases
-- *.tag.ong.br, e os IDs abaixo são específicos do banco de Pirambu;
-- rodar sem o filtro corrompe metadados de outras redes).
-- =============================================================================
--
-- PROBLEMA: em três estruturas de notas de Pirambu, as unidades de
-- "Recuperação de Semestre" foram cadastradas como unidades comuns
-- (grade_unity.type='U') em vez de usar o mecanismo de recuperação
-- parcial (tabela grade_partial_recovery). Isso faz a nota de
-- recuperação ser tratada como se fosse mais uma nota comum na média,
-- além de deslocar a posição das unidades reais que vêm depois dela
-- (grade_results.grade_1..grade_8 é preenchido por posição ordinal).
--
-- Confirmado que outras redes neste mesmo banco (grade_rules 12 e 18)
-- já usam corretamente o cálculo "Média Semestral"
-- (grade_calculation.id = 6) para este mesmo cenário — usado aqui como
-- referência.
--
-- Estruturas corrigidas:
--   A) ENSINO FUNDAMENTAL - 3º AO 9º ANO   (grade_rules.id = 31)
--   B) EJA MODULAR 1ª FASE                 (grade_rules.id = 32)
--   C) EJA - MODULAR - 2ª FASE             (grade_rules.id = 33)
--
-- Para cada estrutura, o script:
--   1) Cria a(s) recuperação(ões) parcial(is) corretas em
--      grade_partial_recovery e liga as unidades reais a elas via
--      grade_unity.parcial_recovery_fk
--   2) Habilita grade_rules.has_partial_recovery
--   3) Migra as notas já lançadas na unidade errada (tabela `grade`,
--      hoje penduradas em grade_unity_modality) para o campo
--      grade_partial_recovery_fk correto
--   4) Só então apaga a unidade errada — nesse ponto a exclusão em
--      cascata (grade_unity -> grade_unity_modality -> grade, ambas
--      ON DELETE CASCADE) não encontra mais nenhuma nota pendurada
--      nela, então nada é perdido
--
-- IMPORTANTE — NÃO recalculado por este script: grade_results é uma
-- tabela de cache, recalculada pela aplicação (CalculateNumericGradeUsecase)
-- a partir da tabela `grade` sempre que uma nota é lançada/relançada
-- na tela de notas. Os valores em cache das unidades deslocadas e das
-- médias de semestre continuam desatualizados para os lançamentos já
-- existentes até esse recálculo rodar de novo (reabrindo e salvando as
-- notas da turma/disciplina, ou por um script à parte que chame esse
-- usecase para as turmas afetadas). O boletim pode continuar exibindo
-- os valores antigos até lá.
--
-- Testado em 2026-09-01 contra uma cópia local do banco de Pirambu,
-- dentro de transação com ROLLBACK — confirmado que todas as notas já
-- lançadas nas unidades erradas (2.245 + 184 na estrutura A, 0 na
-- estrutura B, 83 na estrutura C) migram integralmente e que cada
-- estrutura fica só com suas unidades reais.
--
-- Faça backup/snapshot da tabela `grade` antes de rodar em produção.
-- =============================================================================

START TRANSACTION;

-- =============================================================================
-- A) ENSINO FUNDAMENTAL - 3º AO 9º ANO (grade_rules.id = 31)
--
-- grade_unity antes da correção:
--   732  1ª UNIDADE               semestre 1
--   733  2ª UNIDADE               semestre 1
--   734  RECUPERAÇÃO 1º SEMESTRE  semestre 1   <- cadastrada errado
--   735  3ª UNIDADE               semestre 2
--   736  4ª UNIDADE               semestre 2
--   737  RECUPERAÇÃO 2º SEMESTRE  semestre 2   <- cadastrada errado
-- =============================================================================

SET @a_already_fixed = (
    SELECT COUNT(*) FROM grade_partial_recovery
    WHERE grade_rules_fk = 31 AND semester IN (1, 2)
);

INSERT INTO grade_partial_recovery (name, order_partial_recovery, grade_rules_fk, grade_calculation_fk, semester, created_at, updated_at)
SELECT 'RECUPERAÇÃO 1º SEMESTRE', 1, 31, 6, 1, NOW(), NOW() WHERE @a_already_fixed = 0;
SET @a_rec_sem1 = IF(@a_already_fixed = 0, LAST_INSERT_ID(), NULL);

INSERT INTO grade_partial_recovery (name, order_partial_recovery, grade_rules_fk, grade_calculation_fk, semester, created_at, updated_at)
SELECT 'RECUPERAÇÃO 2º SEMESTRE', 2, 31, 6, 2, NOW(), NOW() WHERE @a_already_fixed = 0;
SET @a_rec_sem2 = IF(@a_already_fixed = 0, LAST_INSERT_ID(), NULL);

UPDATE grade_unity SET parcial_recovery_fk = @a_rec_sem1 WHERE id IN (732, 733) AND @a_already_fixed = 0;
UPDATE grade_unity SET parcial_recovery_fk = @a_rec_sem2 WHERE id IN (735, 736) AND @a_already_fixed = 0;
UPDATE grade_rules SET has_partial_recovery = 1 WHERE id = 31;

SET @a_rec_sem1 = (SELECT id FROM grade_partial_recovery WHERE grade_rules_fk = 31 AND semester = 1 ORDER BY id LIMIT 1);
SET @a_rec_sem2 = (SELECT id FROM grade_partial_recovery WHERE grade_rules_fk = 31 AND semester = 2 ORDER BY id LIMIT 1);

UPDATE grade g
JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk
SET g.grade_partial_recovery_fk = @a_rec_sem1, g.grade_unity_modality_fk = NULL
WHERE gum.grade_unity_fk = 734;

UPDATE grade g
JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk
SET g.grade_partial_recovery_fk = @a_rec_sem2, g.grade_unity_modality_fk = NULL
WHERE gum.grade_unity_fk = 737;

DELETE FROM grade_unity WHERE id IN (734, 737);

-- =============================================================================
-- B) EJA MODULAR 1ª FASE (grade_rules.id = 32)
--
-- grade_unity antes da correção:
--   739  1ª UNIDADE               semestre 1
--   740  2ª UNIDADE               semestre 1
--   741  RECUPERAÇÃO 1º SEMESTRE  semestre 1   <- cadastrada errado
--   742  3ª UNIDADE               semestre 2
--   743  4ª UNIDADE               semestre 2
--   744  RECUPERAÇÃO 2º SEMESTRE  semestre 2   <- cadastrada errado
--
-- Nenhuma nota havia sido lançada ainda nas unidades erradas desta
-- estrutura (confirmado em 2026-09-01).
-- =============================================================================

SET @b_already_fixed = (
    SELECT COUNT(*) FROM grade_partial_recovery
    WHERE grade_rules_fk = 32 AND semester IN (1, 2)
);

INSERT INTO grade_partial_recovery (name, order_partial_recovery, grade_rules_fk, grade_calculation_fk, semester, created_at, updated_at)
SELECT 'RECUPERAÇÃO 1º SEMESTRE', 1, 32, 6, 1, NOW(), NOW() WHERE @b_already_fixed = 0;
SET @b_rec_sem1 = IF(@b_already_fixed = 0, LAST_INSERT_ID(), NULL);

INSERT INTO grade_partial_recovery (name, order_partial_recovery, grade_rules_fk, grade_calculation_fk, semester, created_at, updated_at)
SELECT 'RECUPERAÇÃO 2º SEMESTRE', 2, 32, 6, 2, NOW(), NOW() WHERE @b_already_fixed = 0;
SET @b_rec_sem2 = IF(@b_already_fixed = 0, LAST_INSERT_ID(), NULL);

UPDATE grade_unity SET parcial_recovery_fk = @b_rec_sem1 WHERE id IN (739, 740) AND @b_already_fixed = 0;
UPDATE grade_unity SET parcial_recovery_fk = @b_rec_sem2 WHERE id IN (742, 743) AND @b_already_fixed = 0;
UPDATE grade_rules SET has_partial_recovery = 1 WHERE id = 32;

SET @b_rec_sem1 = (SELECT id FROM grade_partial_recovery WHERE grade_rules_fk = 32 AND semester = 1 ORDER BY id LIMIT 1);
SET @b_rec_sem2 = (SELECT id FROM grade_partial_recovery WHERE grade_rules_fk = 32 AND semester = 2 ORDER BY id LIMIT 1);

UPDATE grade g
JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk
SET g.grade_partial_recovery_fk = @b_rec_sem1, g.grade_unity_modality_fk = NULL
WHERE gum.grade_unity_fk = 741;

UPDATE grade g
JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk
SET g.grade_partial_recovery_fk = @b_rec_sem2, g.grade_unity_modality_fk = NULL
WHERE gum.grade_unity_fk = 744;

DELETE FROM grade_unity WHERE id IN (741, 744);

-- =============================================================================
-- C) EJA - MODULAR - 2ª FASE (grade_rules.id = 33)
--
-- grade_unity antes da correção:
--   746  1º BIMESTRE               semestre 1
--   747  2º BIMESTRE               semestre 1
--   748  3º BIMESTRE               semestre 1
--   749  RECUPERAÇÃO 1º SEMESTRE   semestre 1   <- cadastrada errado
--   755  Recuperação Final         (sem semestre, type='RF' -- já correta,
--                                   não é tocada por este script)
--
-- Esta estrutura só tem unidades no 1º semestre (não há 2º semestre),
-- então existe apenas UMA recuperação parcial, cobrindo as 3 unidades
-- (e não 2, como nas estruturas A e B acima).
-- =============================================================================

SET @c_already_fixed = (
    SELECT COUNT(*) FROM grade_partial_recovery
    WHERE grade_rules_fk = 33 AND semester = 1
);

INSERT INTO grade_partial_recovery (name, order_partial_recovery, grade_rules_fk, grade_calculation_fk, semester, created_at, updated_at)
SELECT 'RECUPERAÇÃO 1º SEMESTRE', 1, 33, 6, 1, NOW(), NOW() WHERE @c_already_fixed = 0;
SET @c_rec_sem1 = IF(@c_already_fixed = 0, LAST_INSERT_ID(), NULL);

UPDATE grade_unity SET parcial_recovery_fk = @c_rec_sem1 WHERE id IN (746, 747, 748) AND @c_already_fixed = 0;
UPDATE grade_rules SET has_partial_recovery = 1 WHERE id = 33;

SET @c_rec_sem1 = (SELECT id FROM grade_partial_recovery WHERE grade_rules_fk = 33 AND semester = 1 ORDER BY id LIMIT 1);

UPDATE grade g
JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk
SET g.grade_partial_recovery_fk = @c_rec_sem1, g.grade_unity_modality_fk = NULL
WHERE gum.grade_unity_fk = 749;

DELETE FROM grade_unity WHERE id IN (749);

-- =============================================================================
-- Conferência antes do commit
-- =============================================================================

SELECT gpr.grade_rules_fk, gpr.id, gpr.name, gpr.semester, gpr.grade_calculation_fk
FROM grade_partial_recovery gpr WHERE gpr.grade_rules_fk IN (31, 32, 33)
ORDER BY gpr.grade_rules_fk, gpr.semester;

SELECT id, name, grade_rules_fk, semester, parcial_recovery_fk, type
FROM grade_unity WHERE grade_rules_fk IN (31, 32, 33)
ORDER BY grade_rules_fk, id;

SELECT id, name, has_partial_recovery FROM grade_rules WHERE id IN (31, 32, 33);

COMMIT;
