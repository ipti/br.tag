ALTER TABLE grade_rules ADD grade_calculation_fk int(11) DEFAULT 2 NOT NULL;
ALTER TABLE grade_rules ADD has_final_recovery tinyint(1) DEFAULT 0 NOT NULL;
ALTER TABLE grade_rules ADD rule_type varchar(1) DEFAULT 'N' NOT NULL;
ALTER TABLE grade_rules ADD CONSTRAINT grade_rules_FK FOREIGN KEY (grade_calculation_fk) REFERENCES grade_calculation(id);
ALTER TABLE grade_rules DROP FOREIGN KEY grade_rules_ibfk_2;
ALTER TABLE grade_rules DROP FOREIGN KEY grade_rules_ibfk_3;
ALTER TABLE grade_rules DROP FOREIGN KEY grade_rules_ibfk_4;


INSERT INTO grade_calculation (name) VALUES ('Maior');
INSERT INTO grade_calculation (name) VALUES ('Menor');


-- UPDATE RULES BY CONCEPT
CREATE TEMPORARY TABLE UNIDADES_POR_CONCEITO
SELECT edcenso_stage_vs_modality_fk as id  from grade_unity gu WHERE `type` = "UC"
GROUP BY edcenso_stage_vs_modality_fk

UPDATE  grade_rules
	SET rule_type = 'C'
WHERE edcenso_stage_vs_modality_fk  in (SELECT id from UNIDADES_POR_CONCEITO)

-- UPDATE RULES WITH FINAL RECOVERY
CREATE TEMPORARY TABLE IF NOT EXISTS UNIDADES_COM_RECUPERACAO
SELECT edcenso_stage_vs_modality_fk as id  from grade_unity gu WHERE `type` = "RF"
GROUP BY edcenso_stage_vs_modality_fk

UPDATE  grade_rules
	SET has_final_recovery = 1
WHERE edcenso_stage_vs_modality_fk  in (SELECT id from UNIDADES_COM_RECUPERACAO)


