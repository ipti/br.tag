-- CRIAR TABELA INSTANCE_CONFIG PRA EVITAR EXCEPTION NO LOGIN


CREATE TABLE `instance_config` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `parameter_key` varchar(20) NOT NULL,
  `parameter_name` varchar(75) NOT NULL,
  `value` varchar(250) NULL
) AUTO_INCREMENT=0;

INSERT INTO `instance_config` (`parameter_key`, `parameter_name`, `value`)
VALUES ('VHA', 'Valor da Hora-Aula (Minutos)', '60');

INSERT INTO `instance_config` (`parameter_key`, `parameter_name`, `value`)
VALUES ('FEAT_SEDSP', 'Integração com SEDSP', '0');