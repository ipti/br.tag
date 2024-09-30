UPDATE edcenso_stage_vs_modality
SET edcenso_associated_stage_id = CASE name
    WHEN 'Educação infantil - creche (0 a 3 anos)' THEN 1
    WHEN 'Educação infantil - pré-escola (4 e 5 anos)' THEN 2
    WHEN 'Educação infantil - unificada (0 a 5 anos)' THEN 3
    WHEN 'Ensino fundamental de 9 anos - 1º Ano' THEN 14
    WHEN 'Ensino fundamental de 9 anos - 2º Ano' THEN 15
    WHEN 'Ensino fundamental de 9 anos - 3º Ano' THEN 16
    WHEN 'Ensino fundamental de 9 anos - 4º Ano' THEN 17
    WHEN 'Ensino fundamental de 9 anos - 5º Ano' THEN 18
    WHEN 'Ensino fundamental de 9 anos - 6º Ano' THEN 19
    WHEN 'Ensino fundamental de 9 anos - 7º Ano' THEN 20
    WHEN 'Ensino fundamental de 9 anos - 8º Ano' THEN 21
    WHEN 'Ensino fundamental de 9 anos - multi' THEN 22
    WHEN 'Ensino fundamental de 9 anos - 9º Ano' THEN 41
    WHEN 'Ensino fundamental de 9 anos - correção de fluxo' THEN 23
    WHEN 'Educação infantil e ensino fundamental - multietapa' THEN 56
    WHEN 'Ensino médio - 1ª Série' THEN 25
    WHEN 'Ensino médio - 2ª Série' THEN 26
    WHEN 'Ensino médio - 3ª Série' THEN 27
    WHEN 'Ensino médio - 4ª Série' THEN 28
    WHEN 'Ensino médio - não seriada' THEN 29
    WHEN 'Curso técnico integrado (ensino médio integrado) 1ª Série' THEN 30
    WHEN 'Curso técnico integrado (ensino médio integrado) 2ª Série' THEN 31
    WHEN 'Curso técnico integrado (ensino médio integrado) 3ª Série' THEN 32
    WHEN 'Curso técnico integrado (ensino médio integrado) 4ª Série' THEN 33
    WHEN 'Curso técnico integrado (ensino médio integrado) não seriada' THEN 34
    WHEN 'Curso técnico integrado na modalidade EJA (EJA integrada à educação profissional de nível médio)' THEN 74
    WHEN 'Ensino médio - normal/magistério 1ª Série' THEN 35
    WHEN 'Ensino médio - normal/magistério 2ª Série' THEN 36
    WHEN 'Ensino médio - normal/magistério 3ª Série' THEN 37
    WHEN 'Ensino médio - normal/magistério 4ª Série' THEN 38
    WHEN 'Curso técnico - concomitante' THEN 39
    WHEN 'Curso técnico - subsequente' THEN 40
    WHEN 'Curso técnico misto' THEN 64
    WHEN 'EJA - ensino fundamental - anos iniciais' THEN 69
    WHEN 'EJA - ensino fundamental - anos finais' THEN 70
    WHEN 'EJA - ensino fundamental - anos iniciais e anos finais' THEN 72
    WHEN 'EJA - ensino médio' THEN 71
    WHEN 'Curso FIC integrado na modalidade EJA - nível médio' THEN 67
    WHEN 'Curso FIC integrado na modalidade EJA – nível fundamental (EJA integrada à educação profissional de nível fundamental)' THEN 73
    WHEN 'Curso FIC concomitante' THEN 68
    ELSE edcenso_associated_stage_id -- Mantém o valor original se não houver correspondência
END;
