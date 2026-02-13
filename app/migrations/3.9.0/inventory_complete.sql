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


INSERT INTO inventory_item (id,name,unit,description,minimum_stock,created_at,updated_at) VALUES
	 (1,'Papel A4 (Pacote 500 fls)','Pacote','Papel sulfite A4 branco para impressão',10.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (2,'Caneta Esferográfica Azul','Unid','Escrita média cor azul',50.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (3,'Caneta Esferográfica Vermelha','Unid','Escrita média cor vermelha',20.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (4,'Lápis Preto HB nº 2','Unid','Lápis grafite para escrita',100.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (5,'Borracha Branca Escolar','Unid','Borracha macia para grafite',50.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (6,'Apontador de Lápis c/ Depósito','Unid','Apontador plástico com reservatório',30.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (7,'Grampeador de Mesa Médio','Unid','Capacidade para até 20 folhas',5.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (8,'Grampos para Grampeador 26/6','Caixa','Caixa com 5.000 unidades',10.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (9,'Sabão em Pó Multiação','Kg','Para limpeza geral e lavagem',10.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (10,'Detergente Líquido','Frasco','Detergente neutro 500ml',20.00,'2026-02-12 15:32:00','2026-02-12 15:32:00');
INSERT INTO inventory_item (id,name,unit,description,minimum_stock,created_at,updated_at) VALUES
	 (11,'Desinfetante de Ambientes','Litro','Limpador perfumado bactericida',15.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (12,'Papel Higiênico Folha Dupla','Fardo','Fardo com 4 ou 12 rolos',10.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (13,'Água Sanitária','Litro','Hipoclorito de sódio 2%',20.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (14,'Álcool em Gel 70%','Frasco','Frasco de 500ml higienizador',20.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (15,'Caderno Brochura 96 fls','Unid','Capa dura para uso escolar',100.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (16,'Lápis de Cor (12 cores)','Caixa','Caixa de madeira com 12 núcleos',50.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (17,'Cola Branca Líquida','Tubo','Tubo de 90g atóxica',40.00,'2026-02-12 15:32:00','2026-02-12 15:32:00'),
	 (18,'Tesoura Escolar s/ Ponta','Unid','Lâmina de aço inoxidável',30.00,'2026-02-12 15:32:00','2026-02-12 15:32:00');
