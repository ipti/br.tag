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

                if ($classroom->edcensoStageVsModalityFk->id != 3 && $classroom->edcensoStageVsModalityFk->id != 22 && $classroom->edcensoStageVsModalityFk->id != 23
                    && $classroom->edcensoStageVsModalityFk->id != 72 && $classroom->edcensoStageVsModalityFk->id != 56 && $classroom->edcensoStageVsModalityFk->id != 64) {
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
                    foreach ($classroom->attributes as $i => $attr) {
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
                }

                if ($classroom->schooling != '1' || $classroom->pedagogical_mediation_type != '1' || ($classroom->diff_location != '0' && $classroom->diff_location != '1')) {
                    $enrollment['another_scholarization_place'] = '';
                }

                if (($classroom->pedagogical_mediation_type != '1' && $classroom->pedagogical_mediation_type != '2') || $classroom->schooling != '1') {
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
                } else if ($enrollment['public_transport'] == 0) {
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
                }

                if (empty($enrollment['student_inep_id'])) {
                    $student = StudentIdentification::model()->findByPk($enrollment['student_fk']);
                    if (!is_null($student)) {
                        $enrollment['student_inep_id'] = $student->inep_id;
                    }
                }

                $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 60 order by corder', [":year" => $year]);
                foreach ($edcensoAliases as $edcensoAlias) {
                    $register[$edcensoAlias->corder] = $edcensoAlias->default;
                    if ($edcensoAlias["attr"] != null && $enrollment[$edcensoAlias["attr"]] !== $edcensoAlias->default) {
                        $register[$edcensoAlias->corder] = $enrollment[$edcensoAlias["attr"]];
                    }
                }

                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }
}