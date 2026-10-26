-- =============================================================================
-- TAG Migration v3.14.27 - Flag de módulo do MACETE (TASK_MACETE)
-- =============================================================================
-- O MACETE usava só as flags FEAT_DIARY_LESSON_PLAN/FEAT_DIARY_CLASSES, que são
-- as mesmas do Plano de Aula e das Aulas Ministradas já ativas em produção:
-- qualquer município com Plano de Aula ligado passaria a ver o MACETE.
-- TASK_MACETE passa a ser exigida em todo o módulo e nasce DESATIVADA; a rede
-- que for usar o MACETE liga em Administração > Gerenciar Módulos.
-- Idempotente: INSERT IGNORE não altera o valor se a flag já existir.

INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('TASK_MACETE', 1, 'Módulo MACETE', NULL, 'N;');

INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES
('TASK_MACETE', 0, NOW());
