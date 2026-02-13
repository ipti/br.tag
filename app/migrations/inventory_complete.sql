-- TAG Migration: Módulo de Almoxarifado (Completo)
-- Versões 3.8.1 até 3.8.6 integradas

-- 1. Estrutura Base (Catálogo de Itens)
CREATE TABLE IF NOT EXISTS `inventory_item` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `unit` VARCHAR(50) NOT NULL COMMENT 'e.g., Kg, Unidade, Caixa',
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 2. Controle de Estoque (Suporte a Almoxarifado Central com NULL)
CREATE TABLE IF NOT EXISTS `inventory_stock` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_id` INT NOT NULL,
  `school_inep_fk` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `quantity` DECIMAL(10,2) DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_item` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 3. Histórico de Movimentações (Suporte a Almoxarifado Central com NULL)
CREATE TABLE IF NOT EXISTS `inventory_movement` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_id` INT NOT NULL,
  `school_inep_fk` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `user_id` INT NOT NULL,
  `type` TINYINT(1) NOT NULL COMMENT '1: Entrada, 2: Saída',
  `quantity` DECIMAL(10,2) NOT NULL,
  `destination` VARCHAR(255) COMMENT 'Para onde foi enviado / De onde veio',
  `date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_item` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 4. Permissões e Feature Flags
-- Add to AuthItem
INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES 
('FEAT_INVENTORY', 1, 'Módulo de Almoxarifado', NULL, 'N;'),
('TASK_INVENTORY_DASHBOARD', 1, 'Painel de Estoque', NULL, 'N;'),
('TASK_INVENTORY_CATALOG', 1, 'Gerenciar Catálogo de Itens', NULL, 'N;'),
('TASK_INVENTORY_HISTORY', 1, 'Histórico de Movimentações', NULL, 'N;'),
('TASK_INVENTORY_REQUEST', 1, 'Minhas Solicitações de Estoque', NULL, 'N;'),
('TASK_INVENTORY_ADMIN', 1, 'Gerenciar Solicitações (Admin)', NULL, 'N;');

-- Add to Feature Flags (Defaulting to Active)
INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES 
('FEAT_INVENTORY', 1, NOW()),
('TASK_INVENTORY_DASHBOARD', 1, NOW()),
('TASK_INVENTORY_CATALOG', 1, NOW()),
('TASK_INVENTORY_HISTORY', 1, NOW()),
('TASK_INVENTORY_REQUEST', 1, NOW()),
('TASK_INVENTORY_ADMIN', 1, NOW());
