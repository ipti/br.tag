CREATE OR REPLACE 
	VIEW EnrollmentPerClassroom
	AS Select s.id as enrollment, s.`name`, if(s.sex=1, 'M','F') as sex, 
		concat(s.birthday, '<br>',
			TIMESTAMPDIFF(YEAR, str_to_date(s.birthday, '%d/%m/%Y'), CURDATE()), 'a ', 
			TIMESTAMPDIFF(MONTH, str_to_date(s.birthday, '%d/%m/%Y'), CURDATE()) % 12, 'm'
		) as birthday, 
		en.acronym as nation, ec.`name` as city, sd.address, 
		sd.civil_certification as cc, sd.civil_register_enrollment_number cc_new,
		sd.civil_certification_term_number as cc_number, 
		sd.civil_certification_book as cc_book,
		sd.civil_certification_sheet as cc_sheet,
		concat(s.mother_name,"<br>",s.father_name) as parents,
		s.deficiency,
		c.id as classroom_id,
		c.school_year as `year`
	from student_identification as s  
	join student_documents_and_address as sd on (s.id = sd.id)
	left join edcenso_nation as en on (s.edcenso_nation_fk = en.id)
	left join edcenso_city as ec on (s.edcenso_city_fk = ec.id)
	join student_enrollment as se on (s.id = se.student_fk)
	join classroom as c on (se.classroom_fk = c.id)