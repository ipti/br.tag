ALTER TABLE enrollment_online_student_identification
    CHANGE mother_name filiation_1 VARCHAR(100) NULL,
    CHANGE father_name filiation_2 VARCHAR(100) NULL,
    CHANGE zone residence_zone VARCHAR(100) NULL,
    ADD filiation SMALLINT(6) NOT NULL,
    ADD nationality SMALLINT(6) NOT NULL,
    ADD edcenso_nation_fk INT(11) NOT NULL;


INSERT INTO feature_flags (feature_name, active) values
('TASK_ONLINE_ENROLLMENT', 1);
