create table `grade_partial_recovery` (
`id` int NOT NULL AUTO_INCREMENT,
`partial_recover_media` float NOT NULL,
`order_partial_recovery`INT NOT NULL,
`grade_rules_fk` INT NOT NULL,
CONSTRAINT grade_partial_recovery PRIMARY KEY (id),
CONSTRAINT grade_partial_recovery FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id)
);

ALTER TABLE `grade_rules` ADD has_partial_recovery TINYINT DEFAULT 0 NOT NULL;
