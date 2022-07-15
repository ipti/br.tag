<?php

class Register50
{
    public static function export($year)
    {
        $registers = [];

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $classrooms = Classroom::model()->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);
        $instructors = [];

        foreach ($classrooms as $iclass => $classroom) {
            foreach ($classroom->instructorTeachingDatas as $teachingData) {
                if (!isset($instructors[$teachingData->instructor_fk])) {
                    $teachingData->instructorFk->documents->school_inep_id_fk = $school->inep_id;
                    $teachingData->instructorFk->instructorVariableData->school_inep_id_fk = $school->inep_id;
                    $teachingData->instructorFk->school_inep_id_fk = $school->inep_id;

                    $instructors[$teachingData->instructor_fk]['identification'] = $teachingData->instructorFk->attributes;
                    $instructors[$teachingData->instructor_fk]['documents'] = $teachingData->instructorFk->documents->attributes;
                }

                $teachingData->instructor_inep_id = $teachingData->instructorFk->inep_id;
                $teachingData->school_inep_id_fk = $school->inep_id;
                $instructors[$teachingData->instructor_fk]['teaching'][$classroom->id] = $teachingData->attributes;
            }
        }

        foreach ($instructors as $instructor) {
            $id = (String)'90' . $instructor['identification']['id'];

            foreach ($instructor['teaching'] as $teaching) {
                $register = [];

                $teaching['register_type'] = '50';
                $teaching['instructor_fk'] = $id;

                $classroom = Classroom::model()->findByPk($teaching['classroom_id_fk']);
                if ($classroom->edcenso_stage_vs_modality_fk == 1 || $classroom->edcenso_stage_vs_modality_fk == 2 || $classroom->edcenso_stage_vs_modality_fk == 3
                    || ($teaching["role"] != '1' && $teaching["role"] != '5')) {
                    foreach ($teaching as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            $teaching[$i] = '';
                        }
                    }
                } else {
                    $countdisc = 1;
                    foreach ($teaching as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            if (($teaching[$i] >= 99 || $teaching[$i] == 20 || $teaching[$i] == 21)) {
                                if ($countdisc == 1) {
                                    $teaching[$i] = 99;
                                } else {
                                    $teaching[$i] = '';
                                }
                                $countdisc++;
                            }
                        }
                    }
                }

                if ($teaching['role'] != '1' && $teaching['role'] != '5' && $teaching['role'] != '6') {
                    $teaching['contract_type'] = '';
                } else if ($teaching['contract_type'] == '') {
                    $teaching['contract_type'] = '1';
                }

                $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 50 order by corder', [":year" => $year]);
                foreach ($edcensoAliases as $edcensoAlias) {
                    $register[$edcensoAlias->corder] = $edcensoAlias->default;
                    if ($edcensoAlias["attr"] != null && $teaching[$edcensoAlias["attr"]] !== $edcensoAlias->default) {
                        $register[$edcensoAlias->corder] = $teaching[$edcensoAlias["attr"]];
                    }
                }

                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }
}