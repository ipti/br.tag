-- =============================================================================
-- TAG Migration v3.13.13 — Educacenso 2026
-- Ordem: tabelas de referência → colunas → dados → aliases de exportação
-- =============================================================================


-- -----------------------------------------------------------------------------
-- 1. Tabela de eixos de cursos profissionalizantes (referência)
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `edcenso_professional_education_course_axis` (
    `id`   TINYINT(2) UNSIGNED NOT NULL,
    `name` VARCHAR(100)        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `edcenso_professional_education_course_axis` (`id`, `name`) VALUES
(1,  'Ambiente e saúde'),
(2,  'Desenvolvimento educacional e social'),
(3,  'Controle e processos industriais'),
(4,  'Gestão e negócios'),
(5,  'Turismo, hospitalidade e lazer'),
(6,  'Informação e comunicação'),
(7,  'Infraestrutura'),
(8,  'Militar'),
(9,  'Produção alimentícia'),
(10, 'Produção cultural e design'),
(11, 'Produção industrial'),
(12, 'Recursos naturais'),
(13, 'Segurança');


-- -----------------------------------------------------------------------------
-- 2. edcenso_professional_education_course: eixo e carga horária mínima
-- -----------------------------------------------------------------------------

ALTER TABLE `edcenso_professional_education_course`
    ADD COLUMN `axis_id`       TINYINT(2) UNSIGNED NULL AFTER `id`,
    ADD COLUMN `minimum_hours` SMALLINT UNSIGNED   NULL AFTER `axis_id`;

-- Eixo codificado nos primeiros dígitos do id do curso: FLOOR(id / 1000)
UPDATE `edcenso_professional_education_course`
    SET `axis_id` = FLOOR(`id` / 1000)
    WHERE `id` >= 1000;

ALTER TABLE `edcenso_professional_education_course`
    ADD CONSTRAINT `fk_edcenso_professional_education_course_axis`
    FOREIGN KEY (`axis_id`)
    REFERENCES `edcenso_professional_education_course_axis` (`id`);

-- Carga horária mínima por curso (Tabela de Cursos da Educação Profissional 2026)
UPDATE `edcenso_professional_education_course`
    SET `minimum_hours` = CASE `id`
    WHEN 1000 THEN 160
    WHEN 1001 THEN 1200
    WHEN 1002 THEN 1200
    WHEN 1004 THEN 1200
    WHEN 1005 THEN 1200
    WHEN 1006 THEN 1200
    WHEN 1007 THEN 1200
    WHEN 1008 THEN 1200
    WHEN 1009 THEN 1200
    WHEN 1010 THEN 1200
    WHEN 1011 THEN 1200
    WHEN 1012 THEN 1200
    WHEN 1013 THEN 800
    WHEN 1014 THEN 1200
    WHEN 1015 THEN 1200
    WHEN 1016 THEN 1200
    WHEN 1017 THEN 1000
    WHEN 1018 THEN 1200
    WHEN 1019 THEN 1200
    WHEN 1020 THEN 1200
    WHEN 1021 THEN 1200
    WHEN 1022 THEN 1200
    WHEN 1023 THEN 1200
    WHEN 1024 THEN 1200
    WHEN 1025 THEN 1200
    WHEN 1026 THEN 1200
    WHEN 1028 THEN 1200
    WHEN 1029 THEN 800
    WHEN 1030 THEN 1200
    WHEN 1031 THEN 1200
    WHEN 1032 THEN 1200
    WHEN 1033 THEN 1000
    WHEN 1999 THEN 800
    WHEN 2000 THEN 160
    WHEN 2029 THEN 1200
    WHEN 2030 THEN 800
    WHEN 2031 THEN 1200
    WHEN 2032 THEN 1200
    WHEN 2033 THEN 800
    WHEN 2034 THEN 800
    WHEN 2035 THEN 800
    WHEN 2036 THEN 1200
    WHEN 2037 THEN 1200
    WHEN 2038 THEN 1200
    WHEN 2039 THEN 800
    WHEN 2040 THEN 1200
    WHEN 2999 THEN 800
    WHEN 3000 THEN 160
    WHEN 3036 THEN 1200
    WHEN 3037 THEN 1200
    WHEN 3038 THEN 1200
    WHEN 3039 THEN 1200
    WHEN 3040 THEN 1200
    WHEN 3041 THEN 1200
    WHEN 3042 THEN 1200
    WHEN 3043 THEN 1200
    WHEN 3044 THEN 1200
    WHEN 3045 THEN 1200
    WHEN 3048 THEN 1200
    WHEN 3049 THEN 1200
    WHEN 3050 THEN 1200
    WHEN 3051 THEN 1200
    WHEN 3052 THEN 1200
    WHEN 3053 THEN 1200
    WHEN 3054 THEN 1200
    WHEN 3055 THEN 1200
    WHEN 3056 THEN 1200
    WHEN 3057 THEN 1200
    WHEN 3058 THEN 1200
    WHEN 3059 THEN 1200
    WHEN 3060 THEN 1200
    WHEN 3061 THEN 1200
    WHEN 3062 THEN 1200
    WHEN 3063 THEN 1200
    WHEN 3064 THEN 1200
    WHEN 3999 THEN 800
    WHEN 4000 THEN 160
    WHEN 4050 THEN 800
    WHEN 4051 THEN 800
    WHEN 4052 THEN 800
    WHEN 4053 THEN 800
    WHEN 4054 THEN 800
    WHEN 4055 THEN 800
    WHEN 4056 THEN 800
    WHEN 4057 THEN 800
    WHEN 4058 THEN 800
    WHEN 4059 THEN 800
    WHEN 4060 THEN 800
    WHEN 4061 THEN 800
    WHEN 4062 THEN 800
    WHEN 4063 THEN 800
    WHEN 4064 THEN 800
    WHEN 4065 THEN 800
    WHEN 4066 THEN 800
    WHEN 4999 THEN 800
    WHEN 5000 THEN 160
    WHEN 5066 THEN 800
    WHEN 5067 THEN 800
    WHEN 5068 THEN 800
    WHEN 5069 THEN 800
    WHEN 5070 THEN 800
    WHEN 5071 THEN 800
    WHEN 5072 THEN 800
    WHEN 5999 THEN 800
    WHEN 6000 THEN 160
    WHEN 6073 THEN 1200
    WHEN 6074 THEN 1000
    WHEN 6075 THEN 1000
    WHEN 6076 THEN 1000
    WHEN 6077 THEN 1000
    WHEN 6078 THEN 160
    WHEN 6080 THEN 1200
    WHEN 6081 THEN 1000
    WHEN 6082 THEN 1200
    WHEN 6999 THEN 800
    WHEN 7000 THEN 160
    WHEN 7081 THEN 1000
    WHEN 7082 THEN 1200
    WHEN 7083 THEN 1200
    WHEN 7084 THEN 1200
    WHEN 7085 THEN 1200
    WHEN 7086 THEN 1200
    WHEN 7087 THEN 1200
    WHEN 7088 THEN 1200
    WHEN 7089 THEN 1200
    WHEN 7091 THEN 1000
    WHEN 7092 THEN 1200
    WHEN 7093 THEN 1000
    WHEN 7094 THEN 1000
    WHEN 7095 THEN 1000
    WHEN 7096 THEN 160
    WHEN 7097 THEN 1200
    WHEN 7098 THEN 1000
    WHEN 7999 THEN 800
    WHEN 8000 THEN 160
    WHEN 8099 THEN 1200
    WHEN 8100 THEN 1200
    WHEN 8101 THEN 1200
    WHEN 8102 THEN 1200
    WHEN 8103 THEN 1200
    WHEN 8104 THEN 1200
    WHEN 8105 THEN 1200
    WHEN 8106 THEN 1200
    WHEN 8107 THEN 1200
    WHEN 8108 THEN 1200
    WHEN 8109 THEN 1200
    WHEN 8110 THEN 1200
    WHEN 8111 THEN 1200
    WHEN 8112 THEN 1200
    WHEN 8113 THEN 1200
    WHEN 8114 THEN 1200
    WHEN 8115 THEN 1200
    WHEN 8116 THEN 1200
    WHEN 8117 THEN 1200
    WHEN 8118 THEN 1200
    WHEN 8119 THEN 1200
    WHEN 8120 THEN 160
    WHEN 8121 THEN 160
    WHEN 8122 THEN 160
    WHEN 8123 THEN 160
    WHEN 8124 THEN 160
    WHEN 8125 THEN 160
    WHEN 8126 THEN 1200
    WHEN 8127 THEN 160
    WHEN 8128 THEN 160
    WHEN 8129 THEN 160
    WHEN 8130 THEN 1200
    WHEN 8131 THEN 160
    WHEN 8132 THEN 160
    WHEN 8133 THEN 1200
    WHEN 8999 THEN 800
    WHEN 9000 THEN 160
    WHEN 9120 THEN 1200
    WHEN 9121 THEN 1000
    WHEN 9122 THEN 160
    WHEN 9123 THEN 1200
    WHEN 9124 THEN 800
    WHEN 9125 THEN 800
    WHEN 9127 THEN 1200
    WHEN 9999 THEN 800
    WHEN 10000 THEN 160
    WHEN 10128 THEN 1200
    WHEN 10129 THEN 1000
    WHEN 10130 THEN 1200
    WHEN 10131 THEN 800
    WHEN 10132 THEN 1000
    WHEN 10133 THEN 1200
    WHEN 10134 THEN 1000
    WHEN 10135 THEN 1200
    WHEN 10136 THEN 1200
    WHEN 10137 THEN 800
    WHEN 10138 THEN 800
    WHEN 10139 THEN 1200
    WHEN 10140 THEN 800
    WHEN 10141 THEN 800
    WHEN 10143 THEN 800
    WHEN 10144 THEN 1200
    WHEN 10145 THEN 800
    WHEN 10146 THEN 800
    WHEN 10147 THEN 1000
    WHEN 10148 THEN 800
    WHEN 10149 THEN 1200
    WHEN 10150 THEN 800
    WHEN 10151 THEN 800
    WHEN 10152 THEN 1000
    WHEN 10153 THEN 100
    WHEN 10154 THEN 800
    WHEN 10155 THEN 1200
    WHEN 10157 THEN 800
    WHEN 10158 THEN 800
    WHEN 10159 THEN 800
    WHEN 10160 THEN 800
    WHEN 10999 THEN 800
    WHEN 11000 THEN 160
    WHEN 11154 THEN 1200
    WHEN 11155 THEN 1200
    WHEN 11156 THEN 1200
    WHEN 11157 THEN 1200
    WHEN 11158 THEN 1200
    WHEN 11159 THEN 1200
    WHEN 11160 THEN 1200
    WHEN 11161 THEN 1200
    WHEN 11164 THEN 1200
    WHEN 11165 THEN 1200
    WHEN 11166 THEN 1200
    WHEN 11167 THEN 1200
    WHEN 11168 THEN 160
    WHEN 11169 THEN 1200
    WHEN 11170 THEN 1200
    WHEN 11171 THEN 1200
    WHEN 11173 THEN 1200
    WHEN 11174 THEN 1200
    WHEN 11175 THEN 1200
    WHEN 11176 THEN 800
    WHEN 11177 THEN 1200
    WHEN 11178 THEN 1200
    WHEN 11999 THEN 800
    WHEN 12000 THEN 160
    WHEN 12171 THEN 1200
    WHEN 12172 THEN 1200
    WHEN 12173 THEN 1200
    WHEN 12174 THEN 1200
    WHEN 12175 THEN 1000
    WHEN 12176 THEN 1200
    WHEN 12177 THEN 160
    WHEN 12178 THEN 1200
    WHEN 12179 THEN 1200
    WHEN 12180 THEN 1200
    WHEN 12181 THEN 1200
    WHEN 12182 THEN 1000
    WHEN 12184 THEN 1000
    WHEN 12185 THEN 1200
    WHEN 12186 THEN 160
    WHEN 12188 THEN 1200
    WHEN 12999 THEN 800
    WHEN 13000 THEN 160
    WHEN 13181 THEN 800
    WHEN 13182 THEN 1200
    WHEN 13183 THEN 1000
    WHEN 13999 THEN 800
    ELSE NULL END;


-- -----------------------------------------------------------------------------
-- 3. classroom: eixo e carga horária do curso (campos 25 e 27)
-- -----------------------------------------------------------------------------

ALTER TABLE classroom
    ADD COLUMN `qualification_course_axis_code` INT(11) DEFAULT NULL
    AFTER `edcenso_professional_education_course_fk`;

ALTER TABLE classroom
    ADD COLUMN `total_course_hours` INT(11) DEFAULT NULL
    AFTER `qualification_course_axis_code`;


-- -----------------------------------------------------------------------------
-- 4. student_enrollment: carga horária integralizada (registro 60, campo 9)
-- -----------------------------------------------------------------------------

ALTER TABLE student_enrollment
    ADD COLUMN `integrated_course_hours` INT(11) DEFAULT NULL
    AFTER `edcenso_stage_vs_modality_fk`;


-- -----------------------------------------------------------------------------
-- 5. school_structure: novos campos 2026
-- -----------------------------------------------------------------------------

ALTER TABLE school_structure
    ADD COLUMN `dependencies_robotics_lab`               TINYINT(1) NOT NULL DEFAULT 0 AFTER `dependencies_info_lab`;

ALTER TABLE school_structure
    ADD COLUMN `workers_social_worker`                   INT(11)    DEFAULT NULL         AFTER `workers_garden_planting_agricultural`;

ALTER TABLE school_structure
    ADD COLUMN `equipments_audiovisual_student_production` TINYINT(1) NOT NULL DEFAULT 0 AFTER `equipments_equipment_amplification`;

ALTER TABLE school_structure
    ADD COLUMN `equipments_robotics_kit`                 TINYINT(1) NOT NULL DEFAULT 0 AFTER `equipments_garden_planting_agricultural`;

ALTER TABLE school_structure
    ADD COLUMN `equipments_emotional_education_materials` TINYINT(1) NOT NULL DEFAULT 0 AFTER `equipments_educational_games`;


-- -----------------------------------------------------------------------------
-- 6. instructor_variable_data: alfabetização (formação continuada)
-- -----------------------------------------------------------------------------

ALTER TABLE instructor_variable_data
    ADD COLUMN `other_courses_literacy` TINYINT(1) NOT NULL DEFAULT 0
    AFTER `other_courses_pre_school`;


-- -----------------------------------------------------------------------------
-- 7. edcenso_alias: layout de exportação 2026 (deve ser o último passo)
-- -----------------------------------------------------------------------------

START TRANSACTION;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
SELECT src.register, src.corder, src.attr, src.cdesc, src.`default`, src.stable, 2026
FROM edcenso_alias src
WHERE src.`year` = 2025
  AND NOT EXISTS (
      SELECT 1
      FROM edcenso_alias dst
      WHERE dst.`year` = 2026
        AND dst.register = src.register
        AND dst.corder = src.corder
  );

-- Registro 10, campo 115: campo consolidado de dispositivos de acesso à internet.
UPDATE edcenso_alias
SET attr = NULL,
    cdesc = 'Dispositivos usados pelos alunos para acesso a internet',
    `default` = NULL
WHERE `year` = 2026
  AND register = 10
  AND corder = 115;

SET @deleted_register10_device_field := (
    SELECT COUNT(*)
    FROM edcenso_alias
    WHERE `year` = 2026
      AND register = 10
      AND corder = 116
      AND attr = 'internet_access_connected_personaldevice'
);

DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 10
  AND corder = 116
  AND attr = 'internet_access_connected_personaldevice';

UPDATE edcenso_alias
SET corder = corder - @deleted_register10_device_field
WHERE `year` = 2026
  AND register = 10
  AND corder > 116
  AND @deleted_register10_device_field = 1;

-- Registro 10, campo 117: campo consolidado de rede local.
UPDATE edcenso_alias
SET attr = NULL,
    cdesc = 'Rede local interligando computadores',
    `default` = NULL
WHERE `year` = 2026
  AND register = 10
  AND corder = 117;

SET @deleted_register10_network_field := (
    SELECT COUNT(*)
    FROM edcenso_alias
    WHERE `year` = 2026
      AND register = 10
      AND corder = 118
      AND attr = 'internet_access_local_wireless'
);

DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 10
  AND corder = 118
  AND attr = 'internet_access_local_wireless';

UPDATE edcenso_alias
SET corder = corder - @deleted_register10_network_field
WHERE `year` = 2026
  AND register = 10
  AND corder > 118
  AND @deleted_register10_network_field = 1;

-- Registro 20: descrições de campos consolidados.
UPDATE edcenso_alias
SET attr = NULL, cdesc = 'Tipo de turma', `default` = NULL
WHERE `year` = 2026 AND register = 20 AND corder = 14;

UPDATE edcenso_alias
SET attr = NULL, cdesc = 'Forma de organizacao da turma', `default` = NULL
WHERE `year` = 2026 AND register = 20 AND corder = 28;

-- Registro 20, campo 25: Código do eixo do curso de qualificação profissional [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 20 AND corder >= 25;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (20, 25, 'qualification_course_axis_code', 'Codigo do eixo do curso de qualificacao profissional', NULL, NULL, 2026);

-- Registro 20, campo 27: Carga horária total do curso [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 20 AND corder >= 27;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (20, 27, 'total_course_hours', 'Carga horaria total do curso', NULL, NULL, 2026);

-- Registro 10, campo 56: Laboratório de robótica [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 10 AND corder >= 56;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (10, 56, 'dependencies_robotics_lab', 'Laboratório de robótica', '0', NULL, 2026);

-- Registro 10, campo 120: Assistente social [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 10 AND corder >= 120;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (10, 120, 'workers_social_worker', 'Assistente social', NULL, NULL, 2026);

-- Registro 10, campo 144: Equipamentos audiovisuais para produção estudantil [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 10 AND corder >= 144;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (10, 144, 'equipments_audiovisual_student_production', 'Equipamentos audiovisuais para producao estudantil', '0', NULL, 2026);

-- Registro 10, campo 148: Kits de robótica [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 10 AND corder >= 148;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (10, 148, 'equipments_robotics_kit', 'Kits de robotica', '0', NULL, 2026);

-- Registro 10, campo 150: Materiais para educação emocional [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 10 AND corder >= 150;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (10, 150, 'equipments_emotional_education_materials', 'Materiais para educacao emocional e mediacao de conflitos', '0', NULL, 2026);

-- Registro 30/alias 302: Alfabetização (formação continuada) [novo em 2026].
SET @register302_literacy_corder := (
    SELECT corder + 1
    FROM edcenso_alias
    WHERE `year` = 2026
      AND register = 302
      AND attr = 'other_courses_pre_school'
    ORDER BY corder
    LIMIT 1
);

UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026
  AND register = 302
  AND corder >= @register302_literacy_corder
  AND @register302_literacy_corder IS NOT NULL;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
SELECT 302, @register302_literacy_corder, 'other_courses_literacy', 'Alfabetizacao', NULL, NULL, 2026
WHERE @register302_literacy_corder IS NOT NULL;

-- Registro 60, campo 9: Carga horária integralizada pelo aluno [novo em 2026].
UPDATE edcenso_alias
SET corder = corder + 1
WHERE `year` = 2026 AND register = 60 AND corder >= 9;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES (60, 9, 'integrated_course_hours', 'Carga horaria integralizada pelo aluno', NULL, NULL, 2026);

-- Remove campos além do limite do layout 2026.
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND (
      (register = 0   AND corder > 53)
      OR (register = 10  AND corder > 192)
      OR (register = 20  AND corder > 68)
      OR (register IN (301, 302) AND corder > 111)
      OR (register = 40  AND corder > 7)
      OR (register = 50  AND corder > 38)
      OR (register = 60  AND corder > 34)
  );

COMMIT;
