ALTER TABLE enrollment_online_student_identification CHANGE mother_name filiation_1 VARCHAR(100) NULL,
CHANGE father_name filiation_2 VARCHAR(100) NULL,
CHANGE zone residence_zone VARCHAR(100) NULL,
ADD filiation SMALLINT (6) NOT NULL,
ADD nationality SMALLINT (6) NOT NULL,
ADD edcenso_nation_fk INT (11) NOT NULL;

INSERT INTO
    feature_flags (feature_name, active)
values
    ('TASK_ONLINE_ENROLLMENT', 1);

CREATE TABLE
    enrollment_online_pre_enrollment_event (
        id INT (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        start_date DATETIME NOT NULL,
        end_date DATETIME NOT NULL,
        year INT (11) NOT NULL,
        status SMALLINT (6) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

CREATE TABLE
    enrollment_online_event_vs_edcenso_stage (
        id INT (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        pre_enrollment_event_fk INT (11) NOT NULL,
        edcenso_stage_fk INT (11) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_pre_enrollment_event FOREIGN KEY (pre_enrollment_event_fk) REFERENCES enrollment_online_pre_enrollment_event (id),
        CONSTRAINT fk_edcenso_stage FOREIGN KEY (edcenso_stage_fk) REFERENCES edcenso_stage_vs_modality (id)
    );
