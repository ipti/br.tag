insert into auth_item (name, type, description, bizrule, data) values ('foodServiceWorker', 2, 'Merendeira', NULL, NULL);

CREATE TABLE IF NOT EXISTS student_IMC (
    id INT NOT NULL AUTO_INCREMENT,
    height FLOAT NOT NULL,
    weight FLOAT NOT NULL,
    IMC FLOAT NOT NULL,
    observations VARCHAR(500) NULL,
    student_fk INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_student_IMC_student_identification
        FOREIGN KEY (student_fk)
        REFERENCES student_identification(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);


