CREATE OR REPLACE 
	VIEW StudentsDeclaration  
	AS SELECT s.`name`, 
	s.birthday, ec.`name` as birth_city, eu.acronym as birth_uf, 
	sd.civil_certification as cc, sd.civil_register_enrollment_number cc_new,
	sd.civil_certification_term_number as cc_number, 
	sd.civil_certification_book as cc_book,
	sd.civil_certification_sheet as cc_sheet, 
	ecn.`name` as cc_city, 
	eun.acronym as cc_uf,
	s.mother_name as mother,
	s.father_name as father,
	c.school_year as `year`,
	c.`name` as classroom,
	c.turn as turn,
	edsm.`name` as stage,
	c.id as classroom_id,
	s.id as student_id
FROM student_identification as s
	left JOIN edcenso_uf as eu on (s.edcenso_uf_fk = eu.id)
	left JOIN edcenso_city as ec on (s.edcenso_city_fk = ec.id)
	left JOIN edcenso_nation as en on (s.edcenso_nation_fk = en.id)
	JOIN student_documents_and_address as sd on (s.id = sd.id)
	left JOIN edcenso_uf as eun on (sd.notary_office_uf_fk = eun.id)
	left JOIN edcenso_city as ecn on (sd.notary_office_city_fk = ecn.id)
	JOIN student_enrollment as se on (s.id = se.student_fk)
	JOIN classroom as c on (se.classroom_fk = c.id AND c.assistance_type != 4)
	left JOIN edcenso_stage_vs_modality as edsm on (
		(se.edcenso_stage_vs_modality_fk is not null 
		AND edsm.id = se.edcenso_stage_vs_modality_fk) 
		OR (edsm.id = c.edcenso_stage_vs_modality_fk))
	ORDER BY s.`name`;