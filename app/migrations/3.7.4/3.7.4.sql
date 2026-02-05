INSERT INTO grade_unity (name, grade_calculation_fk, `type`, grade_rules_fk)
SELECT
    'Conceito Final',
    1,
    'FC',
    gr.id
FROM grade_rules gr
WHERE gr.rule_type = 'C';

INSERT INTO grade_unity_modality (name, type, grade_unity_fk)
SELECT
    'Avaliação',
    'FC',
    id
FROM grade_unity
WHERE `type` = 'FC';

delete from grade_unity where `type` = 'FC';
