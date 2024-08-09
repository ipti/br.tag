-- ADAPTAR TABELAS AUXILIARES E MAIS ALGUMAS MEXIDAS PRA EVITAR EXCEPTION


ALTER TABLE `school_structure`
ADD `dependencies_recording_and_editing_studio` tinyint(1) NULL AFTER `dependencies_vocational_education_workshop`;

update classroom set pedagogical_mediation_type = 1 where pedagogical_mediation_type = 2;

ALTER TABLE `instructor_identification`
ADD `deficiency_type_monocular_vision` tinyint(1) NULL AFTER `deficiency_type_low_vision`;

ALTER TABLE `student_identification`
ADD `deficiency_type_monocular_vision` tinyint(1) NULL AFTER `deficiency_type_low_vision`;

ALTER TABLE `instructor_variable_data`
ADD `other_courses_bilingual_education_for_the_deaf` tinyint(1) NOT NULL AFTER `other_courses_human_rights_education`,
ADD `other_courses_education_and_tic` tinyint(1) NOT NULL AFTER `other_courses_bilingual_education_for_the_deaf`;

update edcenso_alias set corder = (corder + 1) where corder >= 144 and register = 10 and year = 2023;

insert into edcenso_alias values
(10, 144, 'equipments_material_teachingdeafs', 'Materiais pedagógicos para a educação bilíngue de surdos', null, null, 2023);

ALTER TABLE `school_structure`
ADD `equipments_material_teachingdeafs` tinyint(1) NULL AFTER `equipments_material_sports`;

update school_structure
set equipments_material_teachingdeafs = 0;

update school_structure
set equipments_material_professional_education = 0;