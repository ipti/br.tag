-- =============================================================================
-- TAG Migration v3.13.28 - Alerta de vencimento de estoque passa a ser por escola
-- =============================================================================
-- A v3.13.27 criou o alerta de vencimento em nivel de municipio (um unico
-- prazo padrao e uma unica lista de grupos, compartilhados por todas as
-- escolas). Esta migration refaz essas estruturas para ficarem por escola:
-- cada escola passa a ter seus proprios grupos e seu proprio prazo padrao, e
-- as merendeiras (foodServiceWorker) passam a poder configura-los para a
-- escola delas.
--
-- A feature e recente e ainda nao tem uso relevante em producao, entao as
-- estruturas anteriores (grupo unico, prazo unico) sao substituidas do zero
-- em vez de migradas linha a linha -- cada escola comeca sem grupos e sem
-- prazo padrao configurado, igual a uma instalacao nova.

-- -----------------------------------------------------------------------------
-- 1) Desfaz as estruturas de nivel de municipio da v3.13.27, de forma
--    idempotente (roda de novo sem erro caso ja tenha sido aplicada).
-- -----------------------------------------------------------------------------
SET @food_alert_group_col_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'food'
        AND COLUMN_NAME = 'expiration_alert_group_fk'
);
-- Fallback e "DO 0" (nao "SELECT 1"): um EXECUTE de SELECT deixa um resultset
-- aberto na conexao, e a ferramenta de migration usa consultas nao-
-- bufferizadas, o que quebra o proximo statement.
SET @drop_food_alert_group_fk_sql := IF(
    @food_alert_group_col_exists > 0,
    'ALTER TABLE `food` DROP FOREIGN KEY `fk_food_expiration_alert_group`, DROP COLUMN `expiration_alert_group_fk`',
    'DO 0'
);
PREPARE drop_food_alert_group_fk_stmt FROM @drop_food_alert_group_fk_sql;
EXECUTE drop_food_alert_group_fk_stmt;

SET @food_expiration_alert_group_has_school := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'food_expiration_alert_group'
        AND COLUMN_NAME = 'school_fk'
);
SET @drop_old_group_table_sql := IF(
    @food_expiration_alert_group_has_school = 0,
    'DROP TABLE IF EXISTS `food_expiration_alert_group`',
    'DO 0'
);
PREPARE drop_old_group_table_stmt FROM @drop_old_group_table_sql;
EXECUTE drop_old_group_table_stmt;

-- Tem WHERE: nao e um DELETE sem filtro, so remove a linha da chave especifica
-- desta feature.
DELETE FROM `instance_config` WHERE `parameter_key` = 'FOODS_EXPIRATION_ALERT_DAYS_DEFAULT';

-- -----------------------------------------------------------------------------
-- 2) Grupo de alerta, agora por escola.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `food_expiration_alert_group` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `school_fk` VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci,
    `name` VARCHAR(100) NOT NULL,
    `alert_days` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_food_expiration_alert_group_school_fk` (`school_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 3) Vinculo alimento <-> grupo. Passa a ser N:N (antes era uma coluna unica
--    em `food`) porque o mesmo alimento pode estar em grupos diferentes em
--    escolas diferentes. Dentro de uma mesma escola, a regra "um alimento so
--    pode estar em um grupo por vez" e garantida pela aplicacao
--    (FoodalertController::assignFoods), nao pelo banco.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `food_expiration_alert_group_food` (
    `group_fk` INT(11) NOT NULL,
    `food_fk` INT(11) NOT NULL,
    PRIMARY KEY (`group_fk`, `food_fk`),
    KEY `idx_food_expiration_alert_group_food_food_fk` (`food_fk`),
    CONSTRAINT `fk_faegf_group` FOREIGN KEY (`group_fk`) REFERENCES `food_expiration_alert_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_faegf_food` FOREIGN KEY (`food_fk`) REFERENCES `food` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 4) Prazo padrao, agora por escola (substitui o uso de `instance_config`).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `food_expiration_alert_school_config` (
    `school_fk` VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci,
    `default_days` INT(11) NULL,
    PRIMARY KEY (`school_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
