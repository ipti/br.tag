CREATE OR REPLACE VIEW student_certificates AS
SELECT
    si.id AS student_fk,
    si.name AS student_name,
    si.birthday,
    si.sex,
    si.filiation_1,
    si.filiation_2,
    se.status,
    c.name AS class_name,
    c.school_year,
    sch.name AS school_name,
    ed.name AS discipline_name,
    ed.id AS discipline_id,
    gr.grade_1,
    gr.grade_2,
    gr.grade_3,
    gr.grade_4,
    gr.final_media,
    sdaa.address,
    ec.name AS city_name,
    eu.acronym AS uf_acronym,
    eu.name AS uf_name,
    esv.alias AS etapa_name
FROM
    student_enrollment se
JOIN student_identification si ON si.id = se.student_fk
JOIN student_documents_and_address sdaa ON sdaa.id = si.id
JOIN classroom c ON c.id = se.classroom_fk
JOIN grade_results gr ON gr.enrollment_fk = se.id
JOIN edcenso_discipline ed ON ed.id = gr.discipline_fk
JOIN edcenso_city ec ON ec.id = si.edcenso_city_fk
JOIN edcenso_uf eu ON eu.id = ec.edcenso_uf_fk
JOIN edcenso_stage_vs_modality esv ON esv.id = c.edcenso_stage_vs_modality_fk
JOIN school_identification sch ON sch.inep_id = c.school_inep_fk
WHERE
    se.status = 9;
