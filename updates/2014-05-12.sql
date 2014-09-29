ALTER TABLE `class` DROP FOREIGN KEY `class_discipline_fkey` ;
ALTER TABLE `class` CHANGE COLUMN `discipline_fk` `discipline_fk` INT(11) NULL  , 
  ADD CONSTRAINT `class_discipline_fkey`
  FOREIGN KEY (`discipline_fk` )
  REFERENCES `TAG_SGE`.`edcenso_discipline` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
