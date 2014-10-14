SET lc_time_names = "pt_BR";
CREATE OR REPLACE 
	VIEW ataPerformance
	AS SELECT
		s.`name` as school, concat(ec.`name`,' - ',eu.acronym) AS city,
		DATE_FORMAT(NOW(),'%d') as `day`, 
		CONCAT(UCASE(LEFT(DATE_FORMAT(NOW(),'%M'), 1)), SUBSTRING(DATE_FORMAT(NOW(),'%M'), 2)) as `month`, 
		DATE_FORMAT(NOW(),'%Y') as `year`,
		SUBSTRING_INDEX( svm.`name` , ' - ', 1 ) AS ensino,
		c.`name`,
		CASE c.turn 
			WHEN 'M' THEN 'Matutino'
			WHEN 'T' THEN 'Vespertino'
			ELSE 'Noturno'
		END as turn,
		SUBSTRING_INDEX( svm.`name` , ' - ', -1 ) AS serie,
		c.school_year,
		c.id as classroom_id,
		CONCAT_WS('|',
			if(c.discipline_biology = 1, 	'Biologia', null),
			if(c.discipline_science = 1, 	'Ciência', null), 
			if(c.discipline_physical_education = 1, 	'Educação Física', null),
			if(c.discipline_religious = 1, 	'Ensino Religioso', null), 
			if(c.discipline_philosophy = 1,	'Filosofia', null), 
			if(c.discipline_physics = 1, 	'Física', null),
			if(c.discipline_geography = 1, 	'Geografia', null),
			if(c.discipline_history = 1, 	'História', null),
			if(c.discipline_native_language = 1,'Lingua Nativa', null), 
			if(c.discipline_mathematics = 1,'Matemática', null), 
			if(c.discipline_pedagogical = 1,'Pedagogia', null),
			if(c.discipline_language_portuguese_literature = 1, 'Português', null),
			if(c.discipline_chemistry = 1, 	'Química', null),
			if(c.discipline_arts = 1, 		'Ártes', null),
			if(c.discipline_professional_disciplines = 1, 	'Disciplina Proficionalizante', null), 
			if(c.discipline_foreign_language_spanish = 1, 	'Espanhol', null),
			if(c.discipline_social_study = 1,'Estudo Social', null), 
			if(c.discipline_foreign_language_franch = 1, 	'Francês', null), 
			if(c.discipline_foreign_language_english = 1, 	'Inglês', null),
			if(c.discipline_informatics = 1,'Informática', null), 
			if(c.discipline_libras = 1, 	'Libras', null),
			if(c.discipline_foreign_language_other = 1, 	'Outro Idioma', null), 
			if(c.discipline_sociocultural_diversity = 1, 	'Sociedade e Cultura', null),
			if(c.discipline_others = 1, 	'Outras', null)
		) as disciplines
	FROM classroom as c
	join school_identification as s on (c.school_inep_fk = s.inep_id)
	left join edcenso_city as ec on (s.edcenso_city_fk = ec.id)
	left join edcenso_uf as eu on (s.edcenso_uf_fk = eu.id)
	left join edcenso_stage_vs_modality as svm on (c.edcenso_stage_vs_modality_fk = svm.id);