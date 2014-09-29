CREATE OR REPLACE 
	VIEW StudentsFile   
	AS SELECT s.id, s.`name`, ec.`name` as birth_city, s.birthday, eu.acronym as birth_uf,
		en.`name` as nation, sd.address, eca.`name` as adddress_city, eua.acronym as address_uf, sd.cep,
		sd.rg_number as rg, sd.civil_certification as cc, sd.civil_register_enrollment_number cc_new,
		sd.civil_certification_term_number as cc_number, sd.civil_certification_book as cc_book,
		sd.civil_certification_sheet as cc_sheet, ecn.`name` as cc_city, eun.acronym as cc_uf,
		s.mother_name as mother, s.father_name as father
	FROM student_identification as s
	left JOIN edcenso_uf as eu on (s.edcenso_uf_fk = eu.id)
	left JOIN edcenso_city as ec on (s.edcenso_city_fk = ec.id)
	left JOIN edcenso_nation as en on (s.edcenso_nation_fk = en.id)
	JOIN student_documents_and_address as sd on (s.id = sd.id)
	left JOIN edcenso_uf as eua on (sd.edcenso_uf_fk = eua.id)
	left JOIN edcenso_city as eca on (sd.edcenso_city_fk = eca.id)
	left JOIN edcenso_uf as eun on (sd.notary_office_uf_fk = eun.id)
	left JOIN edcenso_city as ecn on (sd.notary_office_city_fk = ecn.id)
	order by s.`name`;

