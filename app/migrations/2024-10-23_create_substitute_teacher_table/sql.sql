CREATE TABLE substitute_instructor(
    id int NOT NULL AUTO_INCREMENT,
    instructor_fk int NOT NULL,
    teaching_data_fk int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (instructor_fk) REFERENCES instructor_identification(id)
);

ALTER TABLE schedule ADD substitute_instructor_fk int,
ADD CONSTRAINT fk_substitute_instructor FOREIGN KEY (substitute_instructor_fk) REFERENCES substitute_instructor(id);
