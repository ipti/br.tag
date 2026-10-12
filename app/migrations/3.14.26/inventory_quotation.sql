-- TAG Migration: Cotação de Preços no Almoxarifado (TCDA-1239)
-- Segue o mesmo padrão já usado em app/migrations/3.9.0/inventory_complete.sql:
-- CREATE TABLE IF NOT EXISTS, FK reais com ON DELETE CASCADE, InnoDB/utf8/utf8_unicode_ci,
-- e as permissões (auth_item + feature_flags) no mesmo arquivo da migration de schema.

-- 1. Cadastro de fornecedores (não existia nenhum conceito de fornecedor no Almoxarifado)
CREATE TABLE IF NOT EXISTS `inventory_supplier` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `document` VARCHAR(20) NULL COMMENT 'CNPJ ou CPF',
  `phone` VARCHAR(20) NULL,
  `email` VARCHAR(150) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 2. Cabeçalho da cotação
CREATE TABLE IF NOT EXISTS `inventory_quotation` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0: Aberta, 1: Encerrada',
  `observation` TEXT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 3. Item cotado: item do almoxarifado x fornecedor x valor, dentro de uma cotação
CREATE TABLE IF NOT EXISTS `inventory_quotation_item` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `quotation_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  `supplier_id` INT NOT NULL,
  `quoted_value` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`quotation_id`) REFERENCES `inventory_quotation` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_item` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`supplier_id`) REFERENCES `inventory_supplier` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 4. Permissões e Feature Flags (nomes idênticos ao enum TFeature em app/components/auth/TFeature.php)
INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('FEAT_INVENTORY_SUPPLIER', 1, 'Gerenciar Fornecedores do Almoxarifado', NULL, 'N;'),
('FEAT_INVENTORY_QUOTATION', 1, 'Cotação de Preços do Almoxarifado', NULL, 'N;');

INSERT IGNORE INTO `feature_flags` (`feature_name`, `active`, `updated_at`) VALUES
('FEAT_INVENTORY_SUPPLIER', 1, NOW()),
('FEAT_INVENTORY_QUOTATION', 1, NOW());
