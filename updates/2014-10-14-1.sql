CREATE TABLE `school_configuration` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `school_inep_id_fk` VARCHAR(8) NOT NULL,
  `morning_initial` TIME NULL,
  `morning_final` TIME NULL,
  `afternoom_initial` TIME NULL,
  `afternoom_final` TIME NULL,
  `night_initial` TIME NULL,
  `night_final` TIME NULL,
  `allday_initial` TIME NULL,
  `allday_final` TIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_school_configuration_1_idx` (`school_inep_id_fk` ASC),
  CONSTRAINT `fk_school_configuration_1`
    FOREIGN KEY (`school_inep_id_fk`)
    REFERENCES `school_identification` (`inep_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
