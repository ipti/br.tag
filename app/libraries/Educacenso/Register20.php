<?php

class Register20
{
    // 2024: 32 ~> 2025: 26

    private const REGISTER_ATTR_TURMA_EDUCACAO_ESPECIAL = 24;
    private const REGISTER_ATTR_ETAPA_AGREGADA = 25;
    private const REGISTER_ATTR_ETAPA = 26;
    // 2024: 34 ~> 2025: 28
    private const REGISTER_ATTR_SERIE = 28;
    // 2024: 35 ~> 2025: 29
    private const REGISTER_ATTR_PERIODO = 29;
    // 2024: 36 ~> 2025: 30
    private const REGISTER_ATTR_CICLO = 30;
    // 2024: 37 ~> 2025: 31
    private const REGISTER_ATTR_GRUPO_NAO_SERIADO = 31;
    // 2024: 38 ~> 2025: 32
    private const REGISTER_ATTR_MODULO = 32;

    // 2024: 39 ~> 2025: 33
    private const REGISTER_ATTR_ALTERNACIA_REGULAR = 33;

    // 2024: 49 ~> 2025: 43
    private const REGISTER_ATTR_QUIMICA = 43;
    private const REGISTER_ATTR_PROJETO_DE_VIDA = 68;
    private const REGISTER_ATTR_OUTRAS_DISCIPLINAS = 69;

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
        foreach ($teachingDatasOfClassroom as $key => $teachingData) {
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

    public static function export($year)
    {
        $registers = [];

        $classrooms = Classroom::model()->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);

        foreach ($classrooms as $iclass => $attributes) {
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

                if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [30, 31, 32, 33, 34, 39, 40, 64, 74])) {
                    $attributes['course'] = '';
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
                if ($attributes['complementary_activity_type_6'] != null) {
                    if (in_array($attributes['complementary_activity_type_6'], $complementaryActivitiesArray)) {
                        $attributes['complementary_activity_type_6'] = '';
                    }
                }

                $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 20 order by corder', [':year' => $year]);
                foreach ($edcensoAliases as $edcensoAlias) {
                    $register[$edcensoAlias->corder] = $edcensoAlias->default;

                    // if ($edcensoAlias->corder == self::REGISTER_ATTR_FORMACAO_GERAL || $edcensoAlias->corder == self::REGISTER_ATTR_ITINERARIO || $edcensoAlias->corder == self::REGISTER_ATTR_NAO_SE_APLICA) {
                    //     if ($attributes["schooling"] == '0') {
                    //         $register[$edcensoAlias->corder] = '';
                    //     } elseif ($edcensoAlias->corder == self::REGISTER_ATTR_FORMACAO_GERAL && in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3, 39, 40, 64, 68])) {
                    //         $register[self::REGISTER_ATTR_FORMACAO_GERAL] = '0';
                    //     } elseif ($edcensoAlias->corder == self::REGISTER_ATTR_NAO_SE_APLICA && in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3, 39, 40, 64, 68])) {
                    //         $register[self::REGISTER_ATTR_NAO_SE_APLICA] = '1';
                    //     }
                    // }
                    // elseif ($edcensoAlias->corder == self::REGISTER_ATTR_MODALIDADE || $edcensoAlias->corder == self::REGISTER_ATTR_ETAPA) {
                    //     $register[$edcensoAlias->corder] = $attributes[$edcensoAlias["attr"]];
                    //     if ($register[self::REGISTER_ATTR_FORMACAO_GERAL] == '' && $register[self::REGISTER_ATTR_NAO_SE_APLICA] == '') {
                    //         $register[$edcensoAlias->corder] = '';
                    //     }
                    // }

                    if ($edcensoAlias->corder === self::REGISTER_ATTR_ETAPA_AGREGADA) {
                        $edcensoAssocietedStage = EdcensoStageVsModality::model()->findByPk($edcensoStageVsModality->edcenso_associated_stage_id);
                        $register[$edcensoAlias->corder] = $edcensoAssocietedStage->aggregated_stage;
                    }

                    if (in_array($edcensoAlias->corder, [7, 8, 9, 10, 11, 12, 13])) {
                        $register[$edcensoAlias->corder] = $attributes['initial_hour'] . ':' . $attributes['initial_minute'] . '-' . $attributes['final_hour'] . ':' . $attributes['final_minute'];
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
                                self::REGISTER_ATTR_MODULO,
                            ]
                        )
                    ) {
                        if ($register[self::REGISTER_ATTR_ETAPA] == '' || in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3])) {
                            $register[$edcensoAlias->corder] = '';
                        } elseif ($edcensoAlias->corder == self::REGISTER_ATTR_SERIE && !in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67])) {
                            $register[self::REGISTER_ATTR_SERIE] = '0';
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_PERIODO) {
                            $register[self::REGISTER_ATTR_PERIODO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[self::REGISTER_ATTR_PERIODO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_CICLO) {
                            $register[self::REGISTER_ATTR_CICLO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 56])) {
                                $register[self::REGISTER_ATTR_CICLO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $register[self::REGISTER_ATTR_CICLO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_GRUPO_NAO_SERIADO) {
                            $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $register[self::REGISTER_ATTR_CICLO] == '0' && $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_MODULO) {
                            $register[self::REGISTER_ATTR_MODULO] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[self::REGISTER_ATTR_MODULO] = '0';
                            }
                        } elseif ($register[self::REGISTER_ATTR_SERIE] == '0' && $register[self::REGISTER_ATTR_PERIODO] == '0' && $register[self::REGISTER_ATTR_CICLO] == '0' && $register[self::REGISTER_ATTR_GRUPO_NAO_SERIADO] == '0' && $register[self::REGISTER_ATTR_MODULO] == '0' && $edcensoAlias->corder == self::REGISTER_ATTR_ALTERNACIA_REGULAR) {
                            $register[self::REGISTER_ATTR_ALTERNACIA_REGULAR] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[self::REGISTER_ATTR_ALTERNACIA_REGULAR] = '0';
                                $register[self::REGISTER_ATTR_SERIE] = '1';
                            }
                        }
                    }

                    if ($edcensoAlias->corder == self::REGISTER_ATTR_PROJETO_DE_VIDA) {
                        if ($attributes['edcenso_stage_vs_modality_fk'] == '' || $attributes['edcenso_stage_vs_modality_fk'] == 1 || $attributes['edcenso_stage_vs_modality_fk'] == 2 || $attributes['edcenso_stage_vs_modality_fk'] == 3) {
                            $register[$edcensoAlias->corder] = '';
                        } else {
                            $register[$edcensoAlias->corder] = '0';
                        }
                    } elseif ($edcensoAlias['attr'] != null && $attributes[$edcensoAlias['attr']] !== $edcensoAlias->default) {
                        $register[$edcensoAlias->corder] = $attributes[$edcensoAlias['attr']];
                    }

                    $clear_coders = new CList(
                        [
                            self::REGISTER_ATTR_TURMA_EDUCACAO_ESPECIAL,
                            self::REGISTER_ATTR_ETAPA_AGREGADA,
                            self::REGISTER_ATTR_ETAPA,
                        ],
                        true
                    );

                    if ($clear_coders->contains($edcensoAlias->corder) || $edcensoAlias->corder >= self::REGISTER_ATTR_QUIMICA && $edcensoAlias->corder <= self::REGISTER_ATTR_OUTRAS_DISCIPLINAS) {
                        if ($attributes['aee'] == '1' || ($attributes['complementary_activity'] == '1' && $attributes['schooling'] == '0')) {
                            $register[$edcensoAlias->corder] = '';
                        }
                    }

                    // if ($clear_coders->contains(strval($edcensoAlias->corder))) {
                    //     if (($attributes["aee"] == '1' || ($attributes["complementary_activity"] == '1') && $attributes["schooling"] == '0')) {
                    //         $register[$edcensoAlias->corder] = '';
                    //     }
                    // }

                    if (in_array($edcensoAlias->corder, [7, 8, 9, 10, 11, 12, 13])) {
                        $register[$edcensoAlias->corder] = $attributes['initial_hour'] . ':' . $attributes['initial_minute'] . '-' . $attributes['final_hour'] . ':' . $attributes['final_minute'];
                    }
                }

                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }
}
