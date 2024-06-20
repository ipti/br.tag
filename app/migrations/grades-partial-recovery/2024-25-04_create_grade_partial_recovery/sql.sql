create table `grade_partial_recovery` (
`id` INT NOT NULL AUTO_INCREMENT,
`name` varchar(50) NOT NULL,
`order_partial_recovery`INT NOT NULL,
`grade_rules_fk` INT NOT NULL,
`grade_calculation_fk` INT NOT NULL,
CONSTRAINT pk_grade_partial_recovery PRIMARY KEY (id),
CONSTRAINT fk_grade_partial_recovery_rules FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id),
CONSTRAINT fk_grade_partial_recovery_calculation FOREIGN KEY (grade_calculation_fk) REFERENCES grade_calculation(id)
);



ALTER TABLE `grade_rules` ADD has_partial_recovery TINYINT DEFAULT 0 NOT NULL;

ALTER TABLE grade_unity ADD COLUMN parcial_recovery_fk INT NULL;
ALTER TABLE grade_unity
ADD CONSTRAINT fk_grade_unity_parcial_recovery
    FOREIGN KEY (parcial_recovery_fk)
    REFERENCES grade_partial_recovery(id)
    ON DELETE SET NULL;
