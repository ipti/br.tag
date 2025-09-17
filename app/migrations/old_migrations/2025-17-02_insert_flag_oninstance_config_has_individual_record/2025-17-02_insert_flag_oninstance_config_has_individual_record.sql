INSERT INTO `instance_config` (`parameter_key`, `parameter_name`, `value`)
VALUES ('HAS_INDIVIDUALRECORD', 'relatório de ficha individual do aluno', '0');

ALTER TABLE instance_config
ADD COLUMN created_at DATETIME DEFAULT NULL,
ADD COLUMN updated_at DATETIME DEFAULT NULL;
