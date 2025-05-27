CREATE TABLE edcenso_aggregated_stage (
	id INT auto_increment NOT NULL,
	name varchar(255) NULL,
	CONSTRAINT edcenso_aggregated_stage_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

--  Auto-generated SQL script #202505201527
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (301,'Educação Infantil');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (302,'Ensino Fundamental ');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (303,'Multi e correção de fluxo');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (304,'Ensino Médio');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (305,'Ensino Médio - Normal/ Magistério');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (306,'Educação de Jovens e Adultos');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (308,'Curso Técnico e FIC - Concomitante ou Subsequente');


ALTER TABLE edcenso_stage_vs_modality ADD aggregated_stage_id INT NULL;

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=301
	WHERE id IN (1,2,3);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=302
	WHERE id IN (14,15,16,17,18,19,20,21,41);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=303
	WHERE id IN (22,23,56);


UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=304
	WHERE id IN (25,26,27,28,29);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=305
	WHERE id IN (35,36,37,38);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=306
	WHERE id IN (69,70,72,71,74,73,67);


UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=308
	WHERE id IN (39,40,64,68);


ALTER TABLE classroom ADD is_special_education TINYINT DEFAULT 0 NOT NULL;


UPDATE edcenso_alias
	SET attr='is_special_education'
	WHERE register=20
		AND corder=24
	    AND `year`=2025;

