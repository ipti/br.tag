create table classroom_vs_grade_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_fk INT NOT NULL,
    grade_rules_fk INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT classroom_vs_grade_rules_fk_classroom FOREIGN KEY (classroom_fk) REFERENCES classroom(id),
    CONSTRAINT classroom_vs_grade_rules_fk_grade_rules FOREIGN KEY (grade_rules_fk) REFERENCES grade_rules(id)
);


