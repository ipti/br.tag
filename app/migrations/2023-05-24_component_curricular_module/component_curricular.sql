
-- Cria tabela com disciplinas de referencia do censo
CREATE TABLE `edcenso_base_disciplines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB; 

-- Copia somente as disciplinas básicas do censo que vão até o id 99
INSERT INTO edcenso_base_disciplines
SELECT * from edcenso_discipline
WHERE id <= 99;

-- Adiciona coluna na tabela original de disciplinas
ALTER TABLE edcenso_discipline ADD edcenso_base_discipline_fk int NOT NULL;

-- Atualiza registros com um valor padrão, caso não seja da base, adiciona como "outro(99)" 
UPDATE edcenso_discipline 
set edcenso_base_discipline_fk = IF(id <= 99, id, 99); 

-- Define chave estrageira entre as duas disciplinas
ALTER TABLE edcenso_discipline ADD CONSTRAINT edcenso_discipline_FK FOREIGN KEY (edcenso_base_discipline_fk) REFERENCES `demo.tag.ong.br`.edcenso_base_disciplines(id);

-- Alterando id para auto_increment, isso evita que o usuário tente alterar o ID's
SET foreign_key_checks = 0;

ALTER TABLE edcenso_discipline MODIFY COLUMN id int(11) auto_increment NOT NULL;

SET foreign_key_checks = 1;

-- Alterando auto_incremet para evitar conflit de chave

SELECT @max := MAX(ID)+ 1 FROM edcenso_discipline;

set @alter_statement = concat('ALTER TABLE edcenso_discipline AUTO_INCREMENT = ', @max);

PREPARE stmt FROM @alter_statement;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;
