CREATE TABLE grade_partial_recovery_weights (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    weight INT NOT NULL,
    unity_fk INT,
    partial_recovery_fk INT NOT NULL,
    FOREIGN KEY (unity_fk) REFERENCES grade_unity(id),
    FOREIGN KEY (partial_recovery_fk) REFERENCES grade_partial_recovery(id)
);
