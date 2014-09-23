CREATE OR REPLACE 
	VIEW InstructorsPerClassroom   
	AS Select c.id, c.`name`,
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
	esm.`name` as stage,
	CONCAT_WS(' - ',if(c.week_days_sunday = 1,'DOMINGO', null),	
		if(c.week_days_monday = 1,'SEGUNDA', null),	
		if(c.week_days_tuesday = 1,'TERÇA', null),	
		if(c.week_days_wednesday = 1,'QUARTA', null),	
		if(c.week_days_thursday = 1,'QUINTA', null),	
		if(c.week_days_friday = 1,'SEXTA', null),	
		if(c.week_days_saturday = 1,'SÁBADO', null)) as week_days,
	inf.id as instructor_id, inf.birthday_date, inf.`name` as instructor_name, 
	CASE ivd.scholarity
		WHEN 1 THEN 'Fundamental Incompleto'
		WHEN 2 THEN 'Fundamental Completo'
		WHEN 3 THEN 'Ensino Médio Normal/Magistério'
		WHEN 4 THEN 'Ensino Médio Normal/Magistério Indígena'
		WHEN 5 THEN 'Ensino Médio'
		ELSE 'Superior'
	END as scholarity,
	CONCAT_WS('<br>',d1.`name`, d2.`name`, d3.`name`, d4.`name`,
		d5.`name`, d6.`name`, d7.`name`, d8.`name`, d9.`name`, 
		d10.`name`, d11.`name`, d12.`name`, d13.`name`) as disciplines,
	c.school_year
	from classroom as c
	join instructor_teaching_data as itd on (itd.classroom_id_fk = c.id)
	join instructor_identification as inf on (inf.id = itd.instructor_fk)
	join instructor_variable_data as ivd on (inf.id = ivd.id)
	join edcenso_stage_vs_modality as esm on (c.edcenso_stage_vs_modality_fk = esm.id)
	left join edcenso_discipline as d1 on (d1.id = itd.discipline_1_fk)
	left join edcenso_discipline as d2 on (d2.id = itd.discipline_2_fk)
	left join edcenso_discipline as d3 on (d3.id = itd.discipline_3_fk)
	left join edcenso_discipline as d4 on (d4.id = itd.discipline_4_fk)
	left join edcenso_discipline as d5 on (d5.id = itd.discipline_5_fk)
	left join edcenso_discipline as d6 on (d6.id = itd.discipline_6_fk)
	left join edcenso_discipline as d7 on (d7.id = itd.discipline_7_fk)
	left join edcenso_discipline as d8 on (d8.id = itd.discipline_8_fk)
	left join edcenso_discipline as d9 on (d9.id = itd.discipline_9_fk)
	left join edcenso_discipline as d10 on (d10.id = itd.discipline_10_fk)
	left join edcenso_discipline as d11 on (d11.id = itd.discipline_11_fk)
	left join edcenso_discipline as d12 on (d12.id = itd.discipline_12_fk)
	left join edcenso_discipline as d13 on (d13.id = itd.discipline_13_fk)
	#where c.school_year = ".$this->year."
	order by c.id;