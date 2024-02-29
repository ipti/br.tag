create table `grade_unity_semianual` (
`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
`grade_unity_fk` int,
`grade_unity_semianual_fk` int
);

ALTER TABLE grade_unity_semianual ADD CONSTRAINT grade_unity_semianual_FK FOREIGN KEY (id) REFERENCES grade_unity(id);
ALTER TABLE grade_unity_semianual ADD CONSTRAINT grade_unity_fk FOREIGN KEY (id) REFERENCES grade_unity(id);

ALTER TABLE `grade_rules`
ADD `semi_recover_media` int NOT NULL;

ALTER TABLE `grade_rules` ADD has_semianual_recovery tinyint(1) DEFAULT 0 NOT NULL;
