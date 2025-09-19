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
