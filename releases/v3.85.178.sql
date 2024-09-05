-- 2024-06-28_reenrollment_student

ALTER TABLE student_enrollment ADD COLUMN class_transfer_date VARCHAR(20);
ALTER TABLE student_enrollment ADD COLUMN school_readmission_date VARCHAR(20);

-- app/migrations/2024-07-11_courseplan_typecolumn_increase/sql

ALTER TABLE `course_class`
CHANGE `type` `type` varchar(1000) COLLATE 'utf8_unicode_ci' NULL AFTER `fkid`;

-- app/migrations/2024-07-23_create_student_aee_report/default.sql

CREATE TABLE student_aee_record (
    id INT(11) NOT NULL AUTO_INCREMENT,
    date DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    learning_needs VARCHAR(500) NULL,
    characterization VARCHAR(1000) NULL,
    student_fk INT(11) NOT NULL,
    school_fk VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci,
    classroom_fk INT(11) NOT NULL,
    instructor_fk INT(11) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT student_aee_record_student_fk FOREIGN KEY (student_fk) REFERENCES student_identification(id)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT student_aee_record_school_fk FOREIGN KEY (school_fk) REFERENCES school_identification(inep_id)
    ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT student_aee_record_classroom_fk FOREIGN KEY (classroom_fk) REFERENCES classroom(id)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT student_aee_record_instructor_fk FOREIGN KEY (instructor_fk) REFERENCES instructor_identification(id)
    ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- app/migrations/2024-07-31_change_courseplan_inputnames/sql.sql

ALTER TABLE course_class CHANGE `type` methodology VARCHAR(1500);

ALTER TABLE course_class CHANGE `objective` content text NOT NULL;

-- app/migrations/2024-17-07_new_recovery_calculation/sql.sql

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


