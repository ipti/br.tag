
-- Cria a coluna `civil_name` na tabela `instructor_identification`
ALTER TABLE `instructor_identification` ADD `civil_name` VARCHAR(100) NOT NULL AFTER `name`;

-- Copia todos os dados da coluna `nome` para a coluna `civil_name`
-- UPDATE `instructor_identification` SET `civil_name` = `name`

