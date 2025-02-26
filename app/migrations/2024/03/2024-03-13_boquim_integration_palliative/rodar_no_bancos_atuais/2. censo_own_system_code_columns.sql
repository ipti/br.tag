-- COMO AS TURMAS, ALUNOS E PROFESSORES PODEM NÃO TER INEP_ID, PRECISA-SE DE COLUNA AUXILIAR PARA MAPEAR OS REGISTROS 50 E 60

ALTER TABLE `classroom`
ADD `censo_own_system_code` varchar(20) NULL;

ALTER TABLE `student_identification`
ADD `censo_own_system_code` varchar(20) NULL;

ALTER TABLE `instructor_identification`
ADD `censo_own_system_code` varchar(20) NULL;