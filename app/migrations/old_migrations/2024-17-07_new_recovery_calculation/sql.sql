insert into grade_calculation (name) values ('Média Semestral');

ALTER TABLE grade_results ADD COLUMN sem_avarage_1 FLOAT NULL;
ALTER TABLE grade_results ADD COLUMN sem_avarage_2 FLOAT NULL;

ALTER TABLE grade_results
    CHANGE COLUMN rec_partial_9 sem_rec_partial_1 float NULL,
    CHANGE COLUMN rec_partial_10 sem_rec_partial_2 float NULL,
    CHANGE COLUMN rec_partial_11 sem_rec_partial_3 float NULL,
    CHANGE COLUMN rec_partial_12 sem_rec_partial_4 float NULL;

ALTER TABLE grade_partial_recovery ADD COLUMN semester INT NULL;

ALTER TABLE grade_unity ADD COLUMN semester INT NULL
