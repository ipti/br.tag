<?php

class Register20
{
    // 2024: 32 ~> 2025: 26 ~> 2026: 24

    private const REGISTER_ATTR_TURMA_EDUCACAO_ESPECIAL = 22;
    private const REGISTER_ATTR_ETAPA_AGREGADA = 23;
    private const REGISTER_ATTR_ETAPA = 24;
    private const REGISTER_ATTR_QUALIFICATION_COURSE_AXIS = 25;
    private const REGISTER_ATTR_TOTAL_COURSE_HOURS = 27;
    // 2024: 34 ~> 2025: 28 ~> 2026: 28
    private const REGISTER_ATTR_SERIE = 28;
    // 2024: 35 ~> 2025: 29 ~> 2026: 29
    private const REGISTER_ATTR_PERIODO = 29;
    // 2024: 36 ~> 2025: 30 ~> 2026: 30
    private const REGISTER_ATTR_CICLO = 30;
    // 2024: 37 ~> 2025: 31 ~> 2026: 31
    private const REGISTER_ATTR_GRUPO_NAO_SERIADO = 31;
    // 2024: 38 ~> 2025: 32 ~> 2026: 32
    private const REGISTER_ATTR_MODULO = 32;

    // 2024: 39 ~> 2025: 33 ~> 2026: 33
    private const REGISTER_ATTR_ALTERNACIA_REGULAR = 33;

    // 2024: 49 ~> 2025: 43 ~> 2026: 39
    private const REGISTER_ATTR_QUIMICA = 43;
    // 2026: 64
    private const REGISTER_ATTR_PROJETO_DE_VIDA = 68;
    // 2026: 65
    private const REGISTER_ATTR_OUTRAS_DISCIPLINAS = 69;
    private const REGISTER_ATTR_FORMA_ORGANIZACAO = 28;
    private const QUALIFICATION_COURSE_AXIS_STAGE_IDS = [67, 68, 73, 75];
    private const PROFESSIONAL_COURSE_STAGE_IDS = [30, 31, 32, 33, 34, 39, 40, 64, 67, 68, 73, 74, 75];

    private static function sanitizeString($string)
    {
        $wh = ['ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Õ', 'Ô', 'Ú', 'Û', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', '°', '.'];
        $by = ['a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'n', 'n', 'c', 'C', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];

        return str_replace($wh, $by, $string);
    }

    private static function findDisc($id)
    {
        $teachingDataDisciplines = [];

        $teachingDatasOfClassroom = Classroom::model()->findByPk($id)->instructorTeachingDatas;
        foreach ($teachingDatasOfClassroom as $teachingData) {
            foreach ($teachingData->teachingMatrixes as $teachingMatrix) {
                if ($teachingMatrix->curricularMatrixFk->discipline_fk > 99 || $teachingMatrix->curricularMatrixFk->discipline_fk == 20 || $teachingMatrix->curricularMatrixFk->discipline_fk == 21) {
                    $teachingDataDisciplines[99] = 99;
                } else {
                    $teachingDataDisciplines[$teachingMatrix->curricularMatrixFk->discipline_fk] = $teachingMatrix->curricularMatrixFk->discipline_fk;
                }
            }
        }

        return $teachingDataDisciplines;
    }

    private static function classroomDisciplines()
    {
        $disciplines = [];
        $disciplines['discipline_chemistry'] = 1;
        $disciplines['discipline_physics'] = 2;
        $disciplines['discipline_mathematics'] = 3;
        $disciplines['discipline_biology'] = 4;
        $disciplines['discipline_science'] = 5;
        $disciplines['discipline_language_portuguese_literature'] = 6;
        $disciplines['discipline_foreign_language_english'] = 7;
        $disciplines['discipline_foreign_language_spanish'] = 8;
        $disciplines['discipline_foreign_language_other'] = 9;
        $disciplines['discipline_arts'] = 10;
        $disciplines['discipline_physical_education'] = 11;
        $disciplines['discipline_history'] = 12;
        $disciplines['discipline_geography'] = 13;
        $disciplines['discipline_philosophy'] = 14;
        $disciplines['discipline_informatics'] = 16;
        $disciplines['discipline_professional_disciplines'] = 17;
        $disciplines['discipline_special_education_and_inclusive_practices'] = 20;
        $disciplines['discipline_sociocultural_diversity'] = 21;
        $disciplines['discipline_libras'] = 23;
        $disciplines['discipline_religious'] = 26;
        $disciplines['discipline_native_language'] = 27;
        $disciplines['discipline_pedagogical'] = 25;
        $disciplines['discipline_social_study'] = 28;
        $disciplines['discipline_sociology'] = 29;
        $disciplines['discipline_foreign_language_franch'] = 30;
        $disciplines['discipline_others'] = 99;

        return $disciplines;
    }

    private static function isSelectedValue($value)
    {
        return $value === '1' || $value === 1;
    }

    private static function isProfessionalCourseType($value)
    {
        return in_array((string)$value, ['1', '2'], true);
    }

    private static function stageRequiresQualificationCourseAxis($stageId)
    {
        return in_array((int)$stageId, self::QUALIFICATION_COURSE_AXIS_STAGE_IDS, true);
    }

    private static function stageAllowsProfessionalCourse($stageId)
    {
        return in_array((int)$stageId, self::PROFESSIONAL_COURSE_STAGE_IDS, true);
    }

    private static function resolveClassTypeFor2026($attributes)
    {
        if (self::isSelectedValue($attributes['aee'] ?? null)) {
            return '5';
        }

        if (self::isSelectedValue($attributes['schooling'] ?? null) && self::isSelectedValue($attributes['complementary_activity'] ?? null)) {
            return '9';
        }

        if (self::isSelectedValue($attributes['complementary_activity'] ?? null)) {
            return '4';
        }

        return '6';
    }

    private static function resolveOrganizationFormFor2026($register)
    {
        if (($register[self::REGISTER_ATTR_MODULO] ?? null) === '1' || ($register[self::REGISTER_ATTR_MODULO] ?? null) === 1) {
            return '5';
        }

        if (($register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] ?? null) === '1' || ($register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] ?? null) === 1) {
            return '4';
        }

        if (($register[self::REGISTER_ATTR_CICLO] ?? null) === '1' || ($register[self::REGISTER_ATTR_CICLO] ?? null) === 1) {
            return '3';
        }

        if (($register[self::REGISTER_ATTR_PERIODO] ?? null) === '1' || ($register[self::REGISTER_ATTR_PERIODO] ?? null) === 1) {
            return '2';
        }

        return '1';
    }

    public static function export($year)
    {
        $registers = [];

        // Para 2026 o shift de -4 nos corders 33+ desloca as disciplinas e o
        // Projeto de Vida para posições menores que as constantes de classe indicam.
        $disciplineCorderStart = (int)$year === 2026 ? 39 : self::REGISTER_ATTR_QUIMICA;
        $disciplineCorderEnd = (int)$year === 2026 ? 65 : self::REGISTER_ATTR_OUTRAS_DISCIPLINAS;
        $projetoDeVidaCorder = (int)$year === 2026 ? 64 : self::REGISTER_ATTR_PROJETO_DE_VIDA;

        $classrooms = Classroom::model()->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);

        foreach ($classrooms as $attributes) {
            $hasEnrolledStudent = false;
            foreach ($attributes->studentEnrollments as $enrollment) {
                if ($enrollment->status == 1 || $enrollment->status == null) {
                    $hasEnrolledStudent = true;
                    break;
                }
            }
            if (count($attributes->instructorTeachingDatas) >= 1 && $hasEnrolledStudent) {
                $register = [];
                // Altera edcenso_stage_vs_modality_fk pela etapa associao ao censo para adaptar etapas criadas
                $edcensoStageVsModality = EdcensoStageVsModality::model()->findByPk($attributes['edcenso_stage_vs_modality_fk']);
                if ($edcensoStageVsModality === null) {
                    continue;
                }
                $attributes['edcenso_stage_vs_modality_fk'] = $edcensoStageVsModality->edcenso_associated_stage_id;

                $attributes['name'] = trim(strtoupper(self::sanitizeString($attributes['name'])));

                if ($attributes['pedagogical_mediation_type'] == null) {
                    $attributes['pedagogical_mediation_type'] = 1;
                }

                if ($attributes['pedagogical_mediation_type'] != '1') {
                    $attributes['initial_hour'] = '';
                    $attributes['initial_minute'] = '';
                    $attributes['final_hour'] = '';
                    $attributes['final_minute'] = '';
                    $attributes['week_days_sunday'] = '';
                    $attributes['week_days_monday'] = '';
                    $attributes['week_days_tuesday'] = '';
                    $attributes['week_days_wednesday'] = '';
                    $attributes['week_days_thursday'] = '';
                    $attributes['week_days_friday'] = '';
                    $attributes['week_days_saturday'] = '';
                    $attributes['schooling'] = '1';
                    $attributes['complementary_activity'] = '0';
                    $attributes['aee'] = '0';
                }

                if ($attributes['aee'] == '1') {
                    $attributes['schooling'] = '0';
                    $attributes['complementary_activity'] = '0';
                }

                if (
                    $attributes['complementary_activity_type_1'] == null && $attributes['complementary_activity_type_2'] == null && $attributes['complementary_activity_type_3'] == null
                    && $attributes['complementary_activity_type_4'] == null && $attributes['complementary_activity_type_5'] == null && $attributes['complementary_activity_type_6'] == null
                ) {
                    $attributes['complementary_activity'] = '0';
                }

                if ($attributes['pedagogical_mediation_type'] != '1') {
                    $attributes['diff_location'] = '';
                } elseif ($attributes['diff_location'] == null) {
                    $attributes['diff_location'] = '0';
                }

                if ($attributes['modality'] == '3') {
                    $attributes['complementary_activity'] = '0';
                }

                if ($attributes['pedagogical_mediation_type'] == '3' && ($attributes['modality'] == null || $attributes['modality'] == '')) {
                    $attributes['modality'] = '1';
                }

                if ($attributes['complementary_activity'] == '0' && $attributes['schooling'] == '0' && $attributes['aee'] == '0') {
                    $attributes['schooling'] = '1';
                }

                if (!self::stageAllowsProfessionalCourse($attributes['edcenso_stage_vs_modality_fk'])) {
                    $attributes['course'] = '';
                }

                if (!self::stageRequiresQualificationCourseAxis($attributes['edcenso_stage_vs_modality_fk'])) {
                    $attributes['qualification_course_axis_code'] = '';
                }

                if (!self::isProfessionalCourseType($attributes['course'])) {
                    $attributes['total_course_hours'] = '';
                }

                if ($attributes['edcenso_stage_vs_modality_fk'] == '' || $attributes['edcenso_stage_vs_modality_fk'] == 1 || $attributes['edcenso_stage_vs_modality_fk'] == 2 || $attributes['edcenso_stage_vs_modality_fk'] == 3) {
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            $attributes[$i] = '';
                        }
                    }
                } else {
                    $dteacher = self::findDisc($attributes['id']);
                    $dclass = self::classroomDisciplines();
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            $attributes[$i] = '0';
                            if (isset($dteacher[$dclass[$i]])) {
                                $attributes[$i] = '1';
                            }
                        }
                    }
                }

                $complementaryActivitiesArray = [];
                if ($attributes['complementary_activity_type_1'] != null) {
                    array_push($complementaryActivitiesArray, $attributes['complementary_activity_type_1']);
                }
                if ($attributes['complementary_activity_type_2'] != null) {
                    if (in_array($attributes['complementary_activity_type_2'], $complementaryActivitiesArray)) {
                        $attributes['complementary_activity_type_2'] = '';
                    } else {
                        array_push($complementaryActivitiesArray, $attributes['complementary_activity_type_2']);
                    }
                }
                if ($attributes['complementary_activity_type_3'] != null) {
                    if (in_array($attributes['complementary_activity_type_3'], $complementaryActivitiesArray)) {
                        $attributes['complementary_activity_type_3'] = '';
                    } else {
                        array_push($complementaryActivitiesArray, $attributes['complementary_activity_type_3']);
                    }
                }
                if ($attributes['complementary_activity_type_4'] != null) {
                    if (in_array($attributes['complementary_activity_type_4'], $complementaryActivitiesArray)) {
                        $attributes['complementary_activity_type_4'] = '';
                    } else {
                        array_push($complementaryActivitiesArray, $attributes['complementary_activity_type_4']);
                    }
                }
                if ($attributes['complementary_activity_type_5'] != null) {
                    if (in_array($attributes['complementary_activity_type_5'], $complementaryActivitiesArray)) {
                        $attributes['complementary_activity_type_5'] = '';
                    } else {
                        array_push($complementaryActivitiesArray, $attributes['complementary_activity_type_5']);
                    }
                }
                if ($attributes['complementary_activity_type_6'] != null && in_array($attributes['complementary_activity_type_6'], $complementaryActivitiesArray)) {
                    $attributes['complementary_activity_type_6'] = '';
                }

                $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 20 order by corder', [':year' => $year]);
                foreach ($edcensoAliases as $edcensoAlias) {
                    $register[$edcensoAlias->corder] = $edcensoAlias->default;

                    if ($edcensoAlias->corder === self::REGISTER_ATTR_ETAPA_AGREGADA) {
                        $edcensoAssocietedStage = EdcensoStageVsModality::model()->findByPk($edcensoStageVsModality->edcenso_associated_stage_id);
                        $register[$edcensoAlias->corder] = $edcensoAssocietedStage->aggregated_stage;
                    }

                    if (
                        in_array(
                            $edcensoAlias->corder,
                            [
                                self::REGISTER_ATTR_ETAPA,
                                self::REGISTER_ATTR_SERIE,
                                self::REGISTER_ATTR_PERIODO,
                                self::REGISTER_ATTR_CICLO,
                                self::REGISTER_ATTR_GRUPO_NAO_SERIADO,
                                self::REGISTER_ATTR_MODULO
                            ]
                        )
                    ) {
                        if ($register[self::REGISTER_ATTR_ETAPA] == '' || in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3])) {
                            $register[$edcensoAlias->corder] = '';
                        } elseif ($edcensoAlias->corder == self::REGISTER_ATTR_SERIE && !in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67, 68, 75])) {
                            $register[self::REGISTER_ATTR_SERIE] = '0';
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_PERIODO) {
                            $register[self::REGISTER_ATTR_PERIODO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 69, 70, 71, 72, 73, 74, 67, 68, 75])) {
                                $register[self::REGISTER_ATTR_PERIODO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_CICLO) {
                            $register[self::REGISTER_ATTR_CICLO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 56])) {
                                $register[self::REGISTER_ATTR_CICLO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $register[self::REGISTER_ATTR_CICLO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_GRUPO_NAO_SERIADO) {
                            $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67, 68, 75])) {
                                $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $register[self::REGISTER_ATTR_CICLO] == '0' && $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_MODULO) {
                            $register[self::REGISTER_ATTR_MODULO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67, 68, 75])) {
                                $register[self::REGISTER_ATTR_MODULO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $register[self::REGISTER_ATTR_CICLO] == '0' && $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] == '0' && $register[self::REGISTER_ATTR_MODULO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_ALTERNACIA_REGULAR) {
                            $register[self::REGISTER_ATTR_ALTERNACIA_REGULAR] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 69, 70, 71, 72, 73, 74, 67, 68, 75])) {
                                $register[self::REGISTER_ATTR_ALTERNACIA_REGULAR] = '0';
                                $register[self::REGISTER_ATTR_SERIE] = '1';
                            }
                        }
                    }

                    if ($edcensoAlias->corder == $projetoDeVidaCorder) {
                        if ($attributes['edcenso_stage_vs_modality_fk'] == '' || $attributes['edcenso_stage_vs_modality_fk'] == 1 || $attributes['edcenso_stage_vs_modality_fk'] == 2 || $attributes['edcenso_stage_vs_modality_fk'] == 3) {
                            $register[$edcensoAlias->corder] = '';
                        } else {
                            $register[$edcensoAlias->corder] = '0';
                        }
                    } elseif ($edcensoAlias['attr'] != null && $attributes[$edcensoAlias['attr']] !== $edcensoAlias->default) {
                        $register[$edcensoAlias->corder] = $attributes[$edcensoAlias['attr']];
                    }

                    $clearCoders = new CList(
                        [
                            self::REGISTER_ATTR_TURMA_EDUCACAO_ESPECIAL,
                        ],
                        true
                    );

                    if ($clearCoders->contains($edcensoAlias->corder) || ($edcensoAlias->corder >= $disciplineCorderStart && $edcensoAlias->corder <= $disciplineCorderEnd && ($attributes['aee'] == '1' || ($attributes['complementary_activity'] == '1' && $attributes['schooling'] == '0')))) {
                        $register[$edcensoAlias->corder] = '';
                    }

                    if (in_array($edcensoAlias->corder, [7, 8, 9, 10, 11, 12, 13])) {
                        $weekDayAttrs = [
                            7 => 'week_days_sunday',
                            8 => 'week_days_monday',
                            9 => 'week_days_tuesday',
                            10 => 'week_days_wednesday',
                            11 => 'week_days_thursday',
                            12 => 'week_days_friday',
                            13 => 'week_days_saturday',
                        ];
                        $dayAttr = $weekDayAttrs[$edcensoAlias->corder];
                        if ($attributes[$dayAttr] == '1' || $attributes[$dayAttr] === 1) {
                            $register[$edcensoAlias->corder] = $attributes['initial_hour'] . ':' . $attributes['initial_minute'] . '-' . $attributes['final_hour'] . ':' . $attributes['final_minute'];
                        } else {
                            $register[$edcensoAlias->corder] = '';
                        }
                    }
                }

                if ((int) $year === 2026) {
                    $register[14] = self::resolveClassTypeFor2026($attributes);

                    // Campo 22: Turma de Educação Especial — obrigatório quando tipo_turma = 6 ou 9.
                    $register[self::REGISTER_ATTR_TURMA_EDUCACAO_ESPECIAL] = in_array($register[14], ['6', '9'])
                        ? (string)($attributes['is_special_education'] ?? '')
                        : '';

                    // Campo 23: Etapa agregada — obrigatório quando tipo_turma = 6 ou 9.
                    if (!in_array($register[14], ['6', '9'])) {
                        $register[self::REGISTER_ATTR_ETAPA_AGREGADA] = '';
                    }

                    // Campo 24: Etapa — obrigatório quando etapa_agregada = 301, 302, 303, 306 ou 308.
                    $etapaAgregadaValue = (string)($register[self::REGISTER_ATTR_ETAPA_AGREGADA] ?? '');
                    if (!in_array($etapaAgregadaValue, ['301', '302', '303', '306', '308'])) {
                        $register[self::REGISTER_ATTR_ETAPA] = '';
                    }

                    // Campo 28: calculado ANTES de limpar os campos 30-32, pois a função lê
                    // os valores intermediários de organização (CICLO/GRUPO/MODULO) que o
                    // bloco especial do loop depositou nessas posições.
                    $register[self::REGISTER_ATTR_FORMA_ORGANIZACAO] = self::resolveOrganizationFormFor2026($register);

                    // Campo 28: EI (etapas 1, 2, 3) não possui forma de organização no INEP 2026.
                    // resolveOrganizationFormFor2026 retorna '1' como default mesmo quando todos os
                    // valores intermediários estão vazios, então limpamos explicitamente aqui.
                    $stageId = (int)($attributes['edcenso_stage_vs_modality_fk'] ?? 0);
                    if (in_array($stageId, [1, 2, 3])) {
                        $register[self::REGISTER_ATTR_FORMA_ORGANIZACAO] = '';
                    }

                    // Campo 29 (Turma de Formação por Alternância): o loop genérico já leu
                    // turma_alternancia do modelo. Para etapas restritas (EI e EF 1º-5º + 56),
                    // o INEP exige obrigatoriamente 0 — nunca vazio, nunca 1.
                    if (in_array($stageId, [1, 2, 3, 14, 15, 16, 17, 18, 56])) {
                        $register[29] = '0';
                    } elseif ($register[29] === null || $register[29] === '') {
                        $register[29] = '';
                    }

                    // Campos 30-32 (FGB, IFA, IFTP) — válidos somente para etapa_agregada 304 ou 305.
                    // Para todas as demais etapas devem ser nulos.
                    if (!in_array($etapaAgregadaValue, ['304', '305'])) {
                        $register[30] = '';
                        $register[31] = '';
                        $register[32] = '';
                    }
                }

                array_push($registers, EducacensoRegisterFormatter::format(20, $register, $year));
            }
        }

        return $registers;
    }
}
