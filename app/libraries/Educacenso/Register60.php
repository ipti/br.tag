<?php
class Register60
{
    public static function export()
    {
        $registers = [];

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $classrooms = Classroom::model()->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);
        $students = [];

        foreach ($classrooms as $iclass => $classroom) {
            foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
                if(!isset($students[$enrollment->student_fk])){
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
                if ($classroom->assistance_type == '5') {
                    $enrollment['another_scholarization_place'] = '';
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
                    foreach ($classroom->attributes  as $i => $attr) {
                        $pos = strstr($i, 'aee_');
                        if ($pos) {
                            $enrollment[$i] = $attr;
                        }
                    }
                    $enrollment['public_transport'] = '';
                    $enrollment['vehicle_type_van'] = '';
                    $enrollment['vehicle_type_microbus'] = '';
                    $enrollment['vehicle_type_bus'] = '';
                    $enrollment['vehicle_type_bike'] = '';
                    $enrollment['vehicle_type_animal_vehicle'] = '';
                    $enrollment['vehicle_type_other_vehicle'] = '';
                    $enrollment['vehicle_type_waterway_boat_5'] = '';
                    $enrollment['vehicle_type_waterway_boat_5_15'] = '';
                    $enrollment['vehicle_type_waterway_boat_15_35'] = '';
                    $enrollment['vehicle_type_waterway_boat_35'] = '';
                    $enrollment['vehicle_type_metro_or_train'] = '';
                    $enrollment['transport_responsable_government'] = '';
                }

                if ($enrollment['another_scholarization_place'] == '3') {
                    $enrollment['another_scholarization_place'] = '1';
                } elseif ($enrollment['another_scholarization_place'] == '1') {
                    $enrollment['another_scholarization_place'] = '2';
                }
    
                if ($classroom->edcensoStageVsModalityFk->id != 3 && $classroom->edcensoStageVsModalityFk->id != 12 && $classroom->edcensoStageVsModalityFk->id != 13 && $classroom->edcensoStageVsModalityFk->id != 22 && $classroom->edcensoStageVsModalityFk->id != 23 && $classroom->edcensoStageVsModalityFk->id != 24 && $classroom->edcensoStageVsModalityFk->id != 72 && $classroom->edcensoStageVsModalityFk->id != 56 && $classroom->edcensoStageVsModalityFk->id != 64) {
                    $enrollment['edcenso_stage_vs_modality_fk'] = '';
                }

                if ($classroom->edcensoStageVsModalityFk->id != 3) {
                    $enrollment['unified_class'] = '';
                }

                if ($enrollment['public_transport'] == 0) {
                    $enrollment['vehicle_type_van'] = '';
                    $enrollment['vehicle_type_microbus'] = '';
                    $enrollment['vehicle_type_bus'] = '';
                    $enrollment['vehicle_type_bike'] = '';
                    $enrollment['vehicle_type_animal_vehicle'] = '';
                    $enrollment['vehicle_type_other_vehicle'] = '';
                    $enrollment['vehicle_type_waterway_boat_5'] = '';
                    $enrollment['vehicle_type_waterway_boat_5_15'] = '';
                    $enrollment['vehicle_type_waterway_boat_15_35'] = '';
                    $enrollment['vehicle_type_waterway_boat_35'] = '';
                    $enrollment['vehicle_type_metro_or_train'] = '';
                    $enrollment['transport_responsable_government'] = '';
                } else {
                    $enrollment['vehicle_type_van'] = '0';
                    $enrollment['vehicle_type_microbus'] = '0';
                    $enrollment['vehicle_type_bus'] = '0';
                    $enrollment['vehicle_type_bike'] = '0';
                    $enrollment['vehicle_type_animal_vehicle'] = '0';
                    $enrollment['vehicle_type_other_vehicle'] = '0';
                    $enrollment['vehicle_type_waterway_boat_5'] = '0';
                    $enrollment['vehicle_type_waterway_boat_5_15'] = '0';
                    $enrollment['vehicle_type_waterway_boat_15_35'] = '0';
                    $enrollment['vehicle_type_waterway_boat_35'] = '0';
                    $enrollment['vehicle_type_metro_or_train'] = '0';
                    $enrollment['transport_responsable_government'] = '0';
                    $isset = 0;

                    foreach ($enrollment as $i => $attr) {
                        $pos = strstr($i, 'vehicle_type_');
                        if ($pos) {
                            if (!empty($enrollment[$i])) {
                                $isset = 1;
                            }
                        }
                    }

                    if (empty($isset)) {
                        $enrollment['vehicle_type_bus'] = '1';
                        $enrollment['transport_responsable_government'] = '2';
                    }
                }

                if (empty($enrollment['student_inep_id'])) {
                    $student = StudentIdentification::model()->findByPk($enrollment['student_fk']);
                    if (!is_null($student)) {
                        $enrollment['student_inep_id'] = $student->inep_id;
                    }
                }

                foreach ($enrollment as $key => $attr) {
                    $alias = EdcensoAlias::model()->findByAttributes(['register' => '60', 'attr' => $key, 'year' => 2021]);
                    if (isset($alias->corder)) {
                        $register[$alias->corder] = $attr;
                    }
                }

                ksort($register);
                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }
}