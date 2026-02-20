-- Migration: Minimal Fix Inventory Auth
-- Ensure new constants exist without triggering FK or unique constraints

INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES 
('TASK_INVENTORY_MANAGE', 1, NOW()),
('FEAT_INVENTORY_DASHBOARD', 1, NOW()),
('FEAT_INVENTORY_CATALOG', 1, NOW()),
('FEAT_INVENTORY_HISTORY', 1, NOW()),
('FEAT_INVENTORY_REQUEST', 1, NOW()),
('FEAT_INVENTORY_ADMIN', 1, NOW());

INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES 
('TASK_INVENTORY_MANAGE', 1, 'Módulo de Almoxarifado', NULL, 'N;'),
('FEAT_INVENTORY_DASHBOARD', 1, 'Painel de Estoque', NULL, 'N;'),
('FEAT_INVENTORY_CATALOG', 1, 'Gerenciar Catálogo de Itens', NULL, 'N;'),
('FEAT_INVENTORY_HISTORY', 1, 'Histórico de Movimentações', NULL, 'N;'),
('FEAT_INVENTORY_REQUEST', 1, 'Minhas Solicitações de Estoque', NULL, 'N;'),
('FEAT_INVENTORY_ADMIN', 1, 'Gerenciar Solicitações (Admin)', NULL, 'N;');
