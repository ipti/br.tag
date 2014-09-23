CREATE OR REPLACE 
	VIEW NumberOfStudentsPerClassroom   
	AS 	Select c.id, c.`name`,
		CONCAT_WS(' - ',CONCAT_WS(':',initial_hour,initial_minute), CONCAT_WS(':',final_hour, final_minute)) as `time`, 
		CASE c.assistance_type 
			WHEN 0 THEN 'NÃO SE APLICA' 
			WHEN 1 THEN 'CLASSE HOSPITALAR' 
			WHEN 2 THEN 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
			WHEN 3 THEN 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR' 
			ELSE 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)'
		END as assistance_type,
		CASE c.modality 
			WHEN 1 THEN 'REGULAR'
			WHEN 2 THEN 'ESPECIAL'
			ELSE 'EJA'
		END as modality,
		esm.`name` as stage, count(c.id) as students, 
		c.school_year
	from classroom as c
	join student_enrollment as se on (c.id = se.classroom_fk)
	join edcenso_stage_vs_modality as esm on (c.edcenso_stage_vs_modality_fk = esm.id)
	#where c.school_year = ".$this->year."
	group by c.id;