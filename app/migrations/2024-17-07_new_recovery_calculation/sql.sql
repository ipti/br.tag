insert into grade_calculation (name) values ('Média Semestral');

ALTER TABLE grade_results ADD COLUMN sem_avarage_1 FLOAT NULL;
ALTER TABLE grade_results ADD COLUMN sem_avarage_2 FLOAT NULL;

ALTER TABLE grade_results ADD COLUMN sem_rec_partial_1 FLOAT NULL;
ALTER TABLE grade_results ADD COLUMN sem_rec_partial_2 FLOAT NULL;

ALTER TABLE grade_partial_recovery ADD COLUMN semester INT NULL;

ALTER TABLE grade_unity ADD COLUMN semester INT NULL
