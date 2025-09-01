Insert into auth_item (name, type, description, bizrule, data) values ('superuser', 2, NULL, NULL, NULL);

ALTER TABLE instance_config
ADD COLUMN superuseraccess TINYINT(1) NOT NULL DEFAULT 0 AFTER value;

INSERT INTO instance_config (parameter_key, parameter_name, value, superuseraccess) VALUES
    ('SCHOOL_MODULE', 'módulo de escolas', 1, 1),
    ('CLASSROOM_MODULE', 'módulo de turmas', 1, 1),
    ('STUDENT_MODULE', 'módulo de alunos', 1, 1),
    ('INSTRUCTOR_MODULE', 'módulo de professores', 1, 1),
    ('CALENDAR_MODULE', 'módulo de calendário escolar', 1, 1),
    ('CURRICULAR_MATRIX_MODULE', 'módulo de matriz curricular', 1, 1),
    ('TIME_SHEET_MODULE', 'módulo de quadro de horário', 1, 1),
    ('ELECTRONIC_DIARY_MODULE', 'módulo de diário eletrônico', 1, 1),
    ('CLASS_DIARY_MODULE', 'módulo de diário de classe', 1, 1),
    ('QUIZ_MODULE', 'módulo de relatórios', 1, 1),
    ('REPORTS_MODULE', 'módulo de relatórios', 1, 1),
    ('FOODS_MODULE', 'módulo de merenda escolar', 1, 1),
    ('INTEGRATIONS_MODULE', 'módulo de integrações', 1, 1),
    ('ADMIN_MODULE', 'módulo de administração', 1, 1),
    ('DASHBOARD_MODULE', 'módulo de gestão de resultados', 1, 1);


