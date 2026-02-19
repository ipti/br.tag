-- Migration: Repair Inventory Schema (Simple Version)
-- Purpose: Add missing columns and tables

-- 1. Add minimum_stock to inventory_item
ALTER TABLE inventory_item ADD COLUMN minimum_stock DECIMAL(10,2) DEFAULT 0 AFTER description;

-- 2. Create inventory_request table
CREATE TABLE IF NOT EXISTS `inventory_request` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `school_inep_fk` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `item_id` INT NOT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `user_id` INT NOT NULL,
  `status` TINYINT(1) DEFAULT 0 COMMENT '0: Pendente, 1: Aprovado, 2: Rejeitado, 3: Entregue',
  `justification` TEXT,
  `observation` TEXT,
  `requested_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_item` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 3. Ensure Auth Items exist
INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES 
('FEAT_INVENTORY_DASHBOARD', 1, 'Painel de Estoque', NULL, 'N;'),
('FEAT_INVENTORY_CATALOG', 1, 'Gerenciar Catálogo de Itens', NULL, 'N;'),
('FEAT_INVENTORY_HISTORY', 1, 'Histórico de Movimentações', NULL, 'N;'),
('FEAT_INVENTORY_REQUEST', 1, 'Minhas Solicitações de Estoque', NULL, 'N;'),
('FEAT_INVENTORY_ADMIN', 1, 'Gerenciar Solicitações (Admin)', NULL, 'N;');

-- 4. Ensure Feature Flags exist
INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES 
('TASK_INVENTORY_MANAGE', 1, NOW()),
('FEAT_INVENTORY_DASHBOARD', 1, NOW()),
('FEAT_INVENTORY_CATALOG', 1, NOW()),
('FEAT_INVENTORY_HISTORY', 1, NOW()),
('FEAT_INVENTORY_REQUEST', 1, NOW()),
('FEAT_INVENTORY_ADMIN', 1, NOW());
