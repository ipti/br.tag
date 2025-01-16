-- app/migrations/2024-12-22_curricularmatrix_decimalworkload/sql

ALTER TABLE `curricular_matrix`
CHANGE `workload` `workload` float NOT NULL AFTER `discipline_fk`;


-- app/migrations/2024-12-11_alter_grade_concept/default.sql

ALTER TABLE grade_concept ADD COLUMN value float NULL;

