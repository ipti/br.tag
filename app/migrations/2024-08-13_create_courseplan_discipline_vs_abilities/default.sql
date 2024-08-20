CREATE TABLE course_plan_discipline_vs_abilities (
    id INT(11) NOT NULL AUTO_INCREMENT,
    course_plan_fk INT(11) NOT NULL,
    discipline_fk INT(11) NOT NULL,
    course_class_fk INT(11) NOT NULL,
    ability_fk INT(11) NOT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    CONSTRAINT course_plan_discipline_vs_abilities_course_plan_fk FOREIGN KEY (course_plan_fk) REFERENCES course_plan(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT course_plan_discipline_vs_abilities_discipline_fk FOREIGN KEY (discipline_fk) REFERENCES edcenso_discipline(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT course_plan_discipline_vs_abilities_course_class_fk FOREIGN KEY (course_class_fk) REFERENCES course_class(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT course_plan_discipline_vs_abilities_ability_fk FOREIGN KEY (ability_fk) REFERENCES course_class_abilities(id)
    ON DELETE CASCADE ON UPDATE CASCADE
)
