<?php

class Register20
{
    private static function sanitizeString($string)
    {
        $wh = ['ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', '°'];
        $by = ['a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '', '', ''];

        return str_replace($wh, $by, $string);
    }

    private static function fixName($name)
    {
        return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($name));
    }

    private static function findDisc($id)
    {
        $teachingDataDisciplines = [];

        $modelTeachingData = Classroom::model()->findByPk($id)->instructorTeachingDatas;
        foreach ($modelTeachingData as $key => $model) {
            $disciplines = ClassroomController::teachingDataDiscipline2array($model);
            foreach ($disciplines as $discipline) {
                if ($discipline->id > 99 || $discipline->id == 20 || $discipline->id == 21) {
                    $teachingDataDisciplines[99] = 99;
                }
                $teachingDataDisciplines[$discipline->id] = $discipline->id;
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
            if (count($attributes->instructorTeachingDatas) >= 1 && count($attributes->studentEnrollments) >= 1) {

                $register = [];

                $attributes['name'] = strtoupper(self::sanitizeString($attributes['name']));

                if ($attributes["pedagogical_mediation_type"] == null) {
                    $attributes["pedagogical_mediation_type"] = 1;
                }

                if ($attributes["pedagogical_mediation_type"] != '1') {
                    $attributes["initial_hour"] = '';
                    $attributes["initial_minute"] = '';
                    $attributes["final_hour"] = '';
                    $attributes["final_minute"] = '';
                    $attributes["week_days_sunday"] = '';
                    $attributes["week_days_monday"] = '';
                    $attributes["week_days_tuesday"] = '';
                    $attributes["week_days_wednesday"] = '';
                    $attributes["week_days_thursday"] = '';
                    $attributes["week_days_friday"] = '';
                    $attributes["week_days_saturday"] = '';
                    $attributes["schooling"] = '1';
                    $attributes["complementary_activity"] = '0';
                    $attributes["aee"] = '0';
                }

                if ($attributes["aee"] == '1') {
                    $attributes["schooling"] = '0';
                    $attributes["complementary_activity"] = '0';
                }

                if ($attributes["complementary_activity_type_1"] == null && $attributes["complementary_activity_type_2"] == null && $attributes["complementary_activity_type_3"] == null
                    && $attributes["complementary_activity_type_4"] == null && $attributes["complementary_activity_type_5"] == null && $attributes["complementary_activity_type_6"] == null) {
                    $attributes["complementary_activity"] = '0';
                }

                if ($attributes["pedagogical_mediation_type"] != '1' && $attributes["pedagogical_mediation_type"] != '2') {
                    $attributes["diff_location"] = '';
                } else if ($attributes["diff_location"] == null) {
                    $attributes["diff_location"] = '0';
                }

                if ($attributes["modality"] == '3') {
                    $attributes["complementary_activity"] = '0';
                }

                if ($attributes["pedagogical_mediation_type"] == '2' && ($attributes["modality"] == null || $attributes["modality"] == "")) {
                    $attributes["modality"] = '3';
                }
                if ($attributes["pedagogical_mediation_type"] == '3' && ($attributes["modality"] == null || $attributes["modality"] == "")) {
                    $attributes["modality"] = '1';
                }

                if ($attributes["complementary_activity"] == '0' && $attributes["schooling"] == '0' && $attributes["aee"] == '0') {
                    $attributes["schooling"] = '1';
                }

                if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [30, 31, 32, 33, 34, 39, 40, 64, 74])) {
                    $attributes['course'] = '';
                }

                if ($attributes["schooling"] == '0' || $attributes['edcenso_stage_vs_modality_fk'] == 1 || $attributes['edcenso_stage_vs_modality_fk'] == 2 || $attributes['edcenso_stage_vs_modality_fk'] == 3) {
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

                if ($attributes["complementary_activity_type_1"] != null) {
                    $attributes["complementary_activity_type_1"] = self::convertComplementaryActivityTypes($attributes["complementary_activity_type_1"]);
                }
                if ($attributes["complementary_activity_type_2"] != null) {
                    $attributes["complementary_activity_type_2"] = self::convertComplementaryActivityTypes($attributes["complementary_activity_type_2"]);
                }
                if ($attributes["complementary_activity_type_3"] != null) {
                    $attributes["complementary_activity_type_3"] = self::convertComplementaryActivityTypes($attributes["complementary_activity_type_3"]);
                }
                if ($attributes["complementary_activity_type_4"] != null) {
                    $attributes["complementary_activity_type_4"] = self::convertComplementaryActivityTypes($attributes["complementary_activity_type_4"]);
                }
                if ($attributes["complementary_activity_type_5"] != null) {
                    $attributes["complementary_activity_type_5"] = self::convertComplementaryActivityTypes($attributes["complementary_activity_type_5"]);
                }
                if ($attributes["complementary_activity_type_6"] != null) {
                    $attributes["complementary_activity_type_6"] = self::convertComplementaryActivityTypes($attributes["complementary_activity_type_6"]);
                }

                $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 20 order by corder', [":year" => $year]);
                foreach ($edcensoAliases as $edcensoAlias) {
                    $register[$edcensoAlias->corder] = $edcensoAlias->default;
                    if ($edcensoAlias->corder == 21 || $edcensoAlias->corder == 22 || $edcensoAlias->corder == 23) {
                        if ($attributes["schooling"] == '0') {
                            $register[$edcensoAlias->corder] = '';
                        } else if ($edcensoAlias->corder == 21 && in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3, 39, 40, 64, 68])) {
                            $register[21] = '0';
                        } else if ($edcensoAlias->corder == 23 && in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3, 39, 40, 64, 68])) {
                            $register[23] = '1';
                        }
                    } else if ($edcensoAlias->corder == 31 || $edcensoAlias->corder == 32) {
                        $register[$edcensoAlias->corder] = $attributes[$edcensoAlias["attr"]];
                        if ($register[21] == '' && $register[23] == '') {
                            $register[$edcensoAlias->corder] = '';
                        }
                    } else if ($edcensoAlias->corder == 34 || $edcensoAlias->corder == 35 || $edcensoAlias->corder == 36 || $edcensoAlias->corder == 37 || $edcensoAlias->corder == 38 || $edcensoAlias->corder == 39) {
                        if ($attributes['edcenso_stage_vs_modality_fk'] == null || in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3])) {
                            $register[$edcensoAlias->corder] = '';
                        } else if ($edcensoAlias->corder == 34 && !in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67])) {
                            $register[34] = '0';
                        } else if ($register[34] == '0' && $edcensoAlias->corder == 35) {
                            $register[35] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[35] = '0';
                            }
                        } else if ($register[34] == '0' && $register[35] == '0' && $edcensoAlias->corder == 36) {
                            $register[36] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 56])) {
                                $register[36] = '0';
                            }
                        } else if ($register[34] == '0' && $register[35] == '0' && $register[36] == '0' && $edcensoAlias->corder == 37) {
                            $register[37] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[37] = '0';
                            }
                        } else if ($register[34] == '0' && $register[35] == '0' && $register[36] == '0' && $register[37] == '0' && $edcensoAlias->corder == 38) {
                            $register[38] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 56, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[38] = '0';
                            }
                        } else if ($register[34] == '0' && $register[35] == '0' && $register[36] == '0' && $register[37] == '0' && $register[38] == '0' && $edcensoAlias->corder == 39) {
                            $register[39] = '1';
                            if (!in_array($attributes['edcenso_stage_vs_modality_fk'], [19, 20, 21, 22, 23, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 69, 70, 71, 72, 73, 74, 67, 68])) {
                                $register[39] = '0';
                                $register[34] = '1';
                            }
                        }
                    } else if ($edcensoAlias->corder == 73) {
                        if ($attributes["schooling"] == '0' || $attributes['edcenso_stage_vs_modality_fk'] == 1 || $attributes['edcenso_stage_vs_modality_fk'] == 2 || $attributes['edcenso_stage_vs_modality_fk'] == 3) {
                            $register[$edcensoAlias->corder] = '';
                        } else {
                            $register[$edcensoAlias->corder] = '0';
                        }
                    } else if ($edcensoAlias["attr"] != null && $attributes[$edcensoAlias["attr"]] !== $edcensoAlias->default) {
                        $register[$edcensoAlias->corder] = $attributes[$edcensoAlias["attr"]];
                    }
                }

                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }

    private static function convertComplementaryActivityTypes($code) {
        switch($code) {
            case "13106":
                return "13104";
            case "16101":
                return "16001";
            case "51002":
                return "13301";
            case "45910":
                return "41007";
            case "10111":
                return "10103";
            case "15001":
                return "15001";
            case "15005":
                return "15004";
            case "1112":
                return "14103";
            case "2":
                return "29999";
            case "22011":
                return "22011";
            case "31001":
                return "31001";
            case "31015":
                return "31017";
            case "31002":
                return "31016";
            case "39999":
                return "39999";
        }
    }
}