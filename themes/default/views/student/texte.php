Rascunho


SELECT
	si.name,
	si.birthday,
	si.sex,
	si.filiation_1,
	si.filiation_2,
	c.name,
	ed.name,
	gr.grade_1,
	gr.grade_2,
	gr.grade_3,
	gr.grade_4,
	gr.final_media,
	sdaa.address
from
	student_enrollment se
join student_identification si  on 
	si.id = se.student_fk 
join student_documents_and_address sdaa on sdaa.id = si.id 
join classroom c on
	c.id = se.classroom_fk
join grade_results gr on
	gr.enrollment_fk = se.id
join edcenso_discipline ed on
	ed.id = gr.discipline_fk
WHERE
	se.student_fk = 2551
	and c.school_year = 2024



