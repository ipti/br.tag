-- CRIAR TABELA TEACHING_MATRIXES PRA EVITAR EXCEPTION NA TELA DO EDUCACENSO

CREATE TABLE `teaching_matrixes` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `teaching_data_fk` int NOT NULL,
  `curricular_matrix_fk` int NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `teaching_matrixes`
ADD FOREIGN KEY (`teaching_data_fk`) REFERENCES `instructor_teaching_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `teaching_matrixes`
ADD FOREIGN KEY (`curricular_matrix_fk`) REFERENCES `curricular_matrix` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE student_enrollment ADD daily_order INT;