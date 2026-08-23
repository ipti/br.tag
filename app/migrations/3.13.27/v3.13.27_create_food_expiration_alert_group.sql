-- =============================================================================
-- TAG Migration v3.13.27 - Alerta de vencimento de estoque (grupos + padrão)
-- =============================================================================
-- Permite configurar quantos dias de antecedência disparam o alerta de
-- vencimento no estoque da Merenda Escolar. Cada alimento pode pertencer a, no
-- máximo, um "grupo de alerta" com seu próprio prazo (ex.: Hortaliças, 15 dias).
-- Alimentos sem grupo usam o prazo padrão do município, configurado em
-- instance_config (FOODS_EXPIRATION_ALERT_DAYS_DEFAULT).

CREATE TABLE IF NOT EXISTS `food_expiration_alert_group` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `alert_days` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Coluna nova em `food`, adicionada de forma idempotente (instalações
-- existentes não têm essa coluna, e rodar de novo não deve falhar).
SET @food_alert_group_col_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'food'
        AND COLUMN_NAME = 'expiration_alert_group_fk'
);
-- Fallback é "DO 0" (não "SELECT 1"): um EXECUTE de SELECT deixa um
-- resultset aberto na conexão, e a ferramenta de migration usa consultas
-- não-bufferizadas, o que quebra o INSERT seguinte com "Cannot execute
-- queries while other unbuffered queries are active".
SET @food_alert_group_col_sql := IF(
    @food_alert_group_col_exists = 0,
    'ALTER TABLE `food` ADD COLUMN `expiration_alert_group_fk` INT(11) NULL, ADD CONSTRAINT `fk_food_expiration_alert_group` FOREIGN KEY (`expiration_alert_group_fk`) REFERENCES `food_expiration_alert_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'DO 0'
);
PREPARE food_alert_group_col_stmt FROM @food_alert_group_col_sql;
EXECUTE food_alert_group_col_stmt;
-- Sem DEALLOCATE PREPARE: o MySQL libera o prepared statement automaticamente
-- ao fim da sessão, e desalocar aqui falha em ferramentas que não garantem a
-- mesma conexão entre statements (já visto em migration anterior).

-- Prazo padrão do município (30 dias), só se ainda não existir.
INSERT INTO `instance_config` (`parameter_key`, `parameter_name`, `value`)
SELECT 'FOODS_EXPIRATION_ALERT_DAYS_DEFAULT', 'Estoque da Merenda - Dias de antecedência para alerta de vencimento (padrão)', '30'
WHERE NOT EXISTS (
    SELECT 1 FROM `instance_config` WHERE `parameter_key` = 'FOODS_EXPIRATION_ALERT_DAYS_DEFAULT'
);
