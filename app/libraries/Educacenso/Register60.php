<?php

class Register60
{
    public static function export($year)
    {
        $registers = [];

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $classrooms = Classroom::model()->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);
        $students = [];

        foreach ($classrooms as $iclass => $classroom) {
            foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
                if (!isset($students[$enrollment->student_fk])) {
                    $enrollment->studentFk->school_inep_id_fk = $school->inep_id;
                    $enrollment->studentFk->documentsFk->school_inep_id_fk = $school->inep_id;
                    $students[$enrollment->student_fk]['identification'] = $enrollment->studentFk->attributes;
                    $students[$enrollment->student_fk]['documents'] = $enrollment->studentFk->documentsFk->attributes;
                }

                $enrollment->school_inep_id_fk = $school->inep_id;
                $students[$enrollment->student_fk]['enrollments'][$ienrollment] = $enrollment->attributes;
            }
        }

        foreach ($students as $student) {
            foreach ($student['enrollments'] as $enrollment) {
                $register = [];

                $enrollment['register_type'] = '60';

                $classroom = Classroom::model()->findByPk($enrollment['classroom_fk']);

                if ($classroom->schooling == 0 || ($classroom->edcensoStageVsModalityFk->id != 3 && $classroom->edcensoStageVsModalityFk->id != 22 && $classroom->edcensoStageVsModalityFk->id != 23
                    && $classroom->edcensoStageVsModalityFk->id != 72 && $classroom->edcensoStageVsModalityFk->id != 56 && $classroom->edcensoStageVsModalityFk->id != 64)) {
                    $enrollment['edcenso_stage_vs_modality_fk'] = '';
                }

                if ($classroom->aee == 0) {
                    foreach ($enrollment as $i => $attr) {
                        $pos = strstr($i, 'aee_');
                        if ($pos) {
                            $enrollment[$i] = '';
                        }
                    }
                } else {
                    $hasAeeFilled = false;
                    foreach ($enrollment as $i => $attr) {
                        $pos = strstr($i, 'aee_');
                        if ($pos) {
                            if ($attr == '1') {
                                $hasAeeFilled = true;
                            }
                            $enrollment[$i] = $attr;
                        }
                    }
                    //preencher um campo aee qualquer quando todos forem zerados
                    if (!$hasAeeFilled) {
                        $enrollment['aee_cognitive_functions'] = '1';
                    }
                    if ($enrollment['aee_cognitive_functions'] == '' || $enrollment['aee_cognitive_functions'] == null) {
                        $enrollment['aee_cognitive_functions'] = '0';
                    }
                    if ($enrollment['aee_autonomous_life'] == '' || $enrollment['aee_autonomous_life'] == null) {
                        $enrollment['aee_autonomous_life'] = '0';
                    }
                    if ($enrollment['aee_curriculum_enrichment'] == '' || $enrollment['aee_curriculum_enrichment'] == null) {
                        $enrollment['aee_curriculum_enrichment'] = '0';
                    }
                    if ($enrollment['aee_accessible_teaching'] == '' || $enrollment['aee_accessible_teaching'] == null) {
                        $enrollment['aee_accessible_teaching'] = '0';
                    }
                    if ($enrollment['aee_libras'] == '' || $enrollment['aee_libras'] == null) {
                        $enrollment['aee_libras'] = '0';
                    }
                    if ($enrollment['aee_portuguese'] == '' || $enrollment['aee_portuguese'] == null) {
                        $enrollment['aee_portuguese'] = '0';
                    }
                    if ($enrollment['aee_soroban'] == '' || $enrollment['aee_soroban'] == null) {
                        $enrollment['aee_soroban'] = '0';
                    }
                    if ($enrollment['aee_braille'] == '' || $enrollment['aee_braille'] == null) {
                        $enrollment['aee_braille'] = '0';
                    }
                    if ($enrollment['aee_mobility_techniques'] == '' || $enrollment['aee_mobility_techniques'] == null) {
                        $enrollment['aee_mobility_techniques'] = '0';
                    }
                    if ($enrollment['aee_caa'] == '' || $enrollment['aee_caa'] == null) {
                        $enrollment['aee_caa'] = '0';
                    }
                    if ($enrollment['aee_optical_nonoptical'] == '' || $enrollment['aee_optical_nonoptical'] == null) {
                        $enrollment['aee_optical_nonoptical'] = '0';
                    }
                }

                //um copia e cola do que se resolve no backend do registro 20 pra nao gerar inconsistencia (schooling, pedagogical_mediation_type e diff_location)
                $pedagogicalMediationType = $classroom->pedagogical_mediation_type == null ? '1' : $classroom->pedagogical_mediation_type;
                $aee = $classroom->aee;
                $complementaryActivity = $classroom->complementary_activity;
                $schooling = $classroom->schooling;
                $diffLocation = $classroom->diff_location;
                if ($pedagogicalMediationType != '1') {
                    $schooling = '1';
                    $aee = '0';
                    $complementaryActivity = '0';
                }
                if ($aee == '1') {
                    $schooling = '0';
                    $complementaryActivity = '0';
                }
                if ($classroom->complementary_activity_type_1 == null && $classroom->complementary_activity_type_2 == null && $classroom->complementary_activity_type_3 == null
                    && $classroom->complementary_activity_type_4 == null && $classroom->complementary_activity_type_5 == null && $classroom->complementary_activity_type_6 == null) {
                    $complementaryActivity = '0';
                }
                if ($pedagogicalMediationType != '1' && $pedagogicalMediationType != '2') {
                    $diffLocation = '';
                } elseif ($diffLocation == null) {
                    $diffLocation = '0';
                }
                if ($classroom->modality == '3') {
                    $complementaryActivity = '0';
                }
                if ($complementaryActivity == '0' && $schooling == '0' && $aee == '0') {
                    $schooling = '1';
                }
                // fim

                if ($schooling != '1' || $pedagogicalMediationType != '1' || ($diffLocation != '0' && $diffLocation != '1')) {
                    $enrollment['another_scholarization_place'] = '';
                }

                if (($pedagogicalMediationType != '1' && $pedagogicalMediationType != '2') || $schooling != '1') {
                    $enrollment['public_transport'] = '';
                    $enrollment['transport_responsable_government'] = '';
                    $enrollment['vehicle_type_bike'] = '';
                    $enrollment['vehicle_type_microbus'] = '';
                    $enrollment['vehicle_type_bus'] = '';
                    $enrollment['vehicle_type_animal_vehicle'] = '';
                    $enrollment['vehicle_type_van'] = '';
                    $enrollment['vehicle_type_other_vehicle'] = '';
                    $enrollment['vehicle_type_waterway_boat_5'] = '';
                    $enrollment['vehicle_type_waterway_boat_5_15'] = '';
                    $enrollment['vehicle_type_waterway_boat_15_35'] = '';
                    $enrollment['vehicle_type_waterway_boat_35'] = '';
                } elseif ($enrollment['public_transport'] == 0) {
                    $enrollment['transport_responsable_government'] = '';
                    $enrollment['vehicle_type_bike'] = '';
                    $enrollment['vehicle_type_microbus'] = '';
                    $enrollment['vehicle_type_bus'] = '';
                    $enrollment['vehicle_type_animal_vehicle'] = '';
                    $enrollment['vehicle_type_van'] = '';
                    $enrollment['vehicle_type_other_vehicle'] = '';
                    $enrollment['vehicle_type_waterway_boat_5'] = '';
                    $enrollment['vehicle_type_waterway_boat_5_15'] = '';
                    $enrollment['vehicle_type_waterway_boat_15_35'] = '';
                    $enrollment['vehicle_type_waterway_boat_35'] = '';
                } else {
                    if (empty($enrollment['transport_responsable_government'])) {
                        $enrollment['transport_responsable_government'] = '2';
                    }

                    $hasVehicle = false;
                    foreach ($enrollment as $i => $attr) {
                        $pos = strstr($i, 'vehicle_type_');
                        if ($pos) {
                            if ($enrollment[$i] == '1') {
                                $hasVehicle = true;
                            }
                        }
                    }
                    if (!$hasVehicle) {
                        $enrollment['vehicle_type_bus'] = '1';
                    }

                    if ($enrollment['vehicle_type_bike'] == '1' && $enrollment['vehicle_type_microbus'] == '1' && $enrollment['vehicle_type_bus'] == '1'
                        && $enrollment['vehicle_type_animal_vehicle'] == '1' && $enrollment['vehicle_type_van'] == '1' && $enrollment['vehicle_type_other_vehicle'] == '1') {
                        $enrollment['vehicle_type_bike'] = '0';
                    }

                    if ($enrollment['vehicle_type_waterway_boat_5'] == '1' && $enrollment['vehicle_type_waterway_boat_5_15'] == '1'
                        && $enrollment['vehicle_type_waterway_boat_15_35'] == '1' && $enrollment['vehicle_type_waterway_boat_35'] == '1') {
                        $enrollment['vehicle_type_waterway_boat_5'] = '0';
                    }

                    if ($enrollment['vehicle_type_bike'] == null) {
                        $enrollment['vehicle_type_bike'] = 0;
                    }
                    if ($enrollment['vehicle_type_microbus'] == null) {
                        $enrollment['vehicle_type_microbus'] = 0;
                    }
                    if ($enrollment['vehicle_type_bus'] == null) {
                        $enrollment['vehicle_type_bus'] = 0;
                    }
                    if ($enrollment['vehicle_type_animal_vehicle'] == null) {
                        $enrollment['vehicle_type_animal_vehicle'] = 0;
                    }
                    if ($enrollment['vehicle_type_van'] == null) {
                        $enrollment['vehicle_type_van'] = 0;
                    }
                    if ($enrollment['vehicle_type_other_vehicle'] == null) {
                        $enrollment['vehicle_type_other_vehicle'] = 0;
                    }
                    if ($enrollment['vehicle_type_waterway_boat_5'] == null) {
                        $enrollment['vehicle_type_waterway_boat_5'] = 0;
                    }
                    if ($enrollment['vehicle_type_waterway_boat_5_15'] == null) {
                        $enrollment['vehicle_type_waterway_boat_5_15'] = 0;
                    }
                    if ($enrollment['vehicle_type_waterway_boat_15_35'] == null) {
                        $enrollment['vehicle_type_waterway_boat_15_35'] = 0;
                    }
                    if ($enrollment['vehicle_type_waterway_boat_35'] == null) {
                        $enrollment['vehicle_type_waterway_boat_35'] = 0;
                    }
                }

                $student = StudentIdentification::model()->findByPk($enrollment['student_fk']);
                if (!is_null($student)) {
                    $enrollment['student_inep_id'] = $student->inep_id;
                }

                $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 60 order by corder', [':year' => $year]);
                foreach ($edcensoAliases as $edcensoAlias) {
                    $register[$edcensoAlias->corder] = $edcensoAlias->default;
                    if ($edcensoAlias['attr'] != null && $enrollment[$edcensoAlias['attr']] !== $edcensoAlias->default) {
                        $register[$edcensoAlias->corder] = $enrollment[$edcensoAlias['attr']];
                    }
                }

                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }
}
