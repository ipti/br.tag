-- Migração de profissionais para alocação com mapeamento de especialidade
-- Mapeia o campo 'speciality' da tabela professional para o 'role' da tabela professional_allocation

INSERT INTO professional_allocation (
    professional_fk, 
    school_inep_fk, 
    school_year, 
    location_type, 
    role, 
    contract_type, 
    workload, 
    created_at, 
    updated_at
)
SELECT 
    id_professional, 
    inep_id_fk, 
    YEAR(NOW()), 
    'school',
    CASE 
        WHEN speciality LIKE '%Horta%' OR speciality LIKE '%Plantio%' OR speciality LIKE '%Agricultura%' THEN 1
        WHEN speciality LIKE '%Administrativo%' OR speciality LIKE '%Auxiliar Adm%' THEN 2
        WHEN speciality LIKE '%Serviços Gerais%' OR speciality LIKE '%ASG%' OR speciality LIKE '%Zelador%' OR speciality LIKE '%Limpeza%' THEN 3
        WHEN speciality LIKE '%Bibliotec%' THEN 4
        WHEN speciality LIKE '%Bombeiro%' THEN 5
        WHEN speciality LIKE '%Coordenador%' THEN 6
        WHEN speciality LIKE '%Fonoaud%' THEN 7
        WHEN speciality LIKE '%Nutri%' THEN 8
        WHEN speciality LIKE '%Psic%' THEN 9
        WHEN speciality LIKE '%Cozin%' OR speciality LIKE '%Merend%' THEN 10
        WHEN speciality LIKE '%Apoio%' OR speciality LIKE '%Pedag%' THEN 11
        WHEN speciality LIKE '%Secretár%' THEN 12
        WHEN speciality LIKE '%Seguran%' OR speciality LIKE '%Vigia%' OR speciality LIKE '%Guarda%' THEN 13
        WHEN speciality LIKE '%Monitor%' THEN 14
        WHEN speciality LIKE '%Braille%' THEN 15
        ELSE 9999 -- Default para ROLE_UNDEFINED se não encontrar correspondência (força validação no Sagres)
    END as role_mapped,
    99,  -- Contrato padrão (CONTRACT_UNDEFINED) - força correção
    40, -- Carga horária padrão
    NOW(), 
    NOW()
FROM professional 
WHERE inep_id_fk IS NOT NULL AND inep_id_fk != ''
-- Evita duplicatas se rodar mais de uma vez no mesmo ano
AND id_professional NOT IN (
    SELECT professional_fk 
    FROM professional_allocation 
    WHERE school_year = YEAR(NOW())
);
