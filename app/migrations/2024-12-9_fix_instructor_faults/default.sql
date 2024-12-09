ALTER TABLE instructor_faults DROP FOREIGN KEY instructor_faults_FK_1;
ALTER TABLE instructor_faults DROP INDEX fk_instructor_faults_2;
ALTER TABLE instructor_faults DROP FOREIGN KEY instructor_faults_FK;
ALTER TABLE instructor_faults DROP INDEX instructor_faults_FK;

ALTER TABLE instructor_faults
ADD CONSTRAINT instructor_teaching_data_fk FOREIGN KEY (instructor_fk)
REFERENCES instructor_teaching_data(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE instructor_faults
ADD CONSTRAINT schedule_fk FOREIGN KEY (schedule_fk)
REFERENCES schedule(id)
ON DELETE CASCADE
ON UPDATE CASCADE;
