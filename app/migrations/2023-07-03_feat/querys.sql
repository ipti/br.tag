ALTER TABLE school_identification ADD hash BIGINT NULL;
ALTER TABLE edcenso_nation ADD hash BIGINT NULL;
ALTER TABLE classroom MODIFY COLUMN hash BIGINT NULL;
ALTER TABLE instructor_identification MODIFY COLUMN hash BIGINT NULL;
ALTER TABLE users ADD hash BIGINT NULL;
ALTER TABLE student_identification MODIFY COLUMN hash BIGINT NULL;
ALTER TABLE instructor_documents_and_address MODIFY COLUMN hash BIGINT NULL;
ALTER TABLE schedule ADD hash BIGINT NULL;
ALTER TABLE curricular_matrix ADD hash BIGINT NULL;

