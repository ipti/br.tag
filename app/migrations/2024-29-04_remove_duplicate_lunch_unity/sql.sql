DELETE lunch_unity
FROM lunch_unity
INNER JOIN (
    SELECT MAX(id) AS id
    FROM lunch_unity
    WHERE name = 'Mililitro'
) AS last_occurrence ON lunch_unity.id = last_occurrence.id
WHERE lunch_unity.name = 'Mililitro';
