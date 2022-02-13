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
                    $teachingData->instructorFk->documents->school_inep_id_fk =  $school->inep_id;
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
            $id = (String) '90' . $instructor['identification']['id'];

            foreach ($instructor['teaching'] as $teaching) {
                $register = [];

                $teaching['register_type'] = '50';
                $teaching['instructor_fk'] = $id;
                $aliases = EdcensoAlias::model()->findAllByAttributes(['register' => '50', 'year' => $year]);
                foreach ($aliases as $kord => $ord) {
                    $register[$ord->corder] = $ord->default;
                }

                $classroom = Classroom::model()->findByPk($teaching['classroom_id_fk']);
                if ($classroom->edcenso_stage_vs_modality_fk == 1 || $classroom->edcenso_stage_vs_modality_fk == 2 || $classroom->edcenso_stage_vs_modality_fk == 3 || $classroom->edcenso_stage_vs_modality_fk == 65) {
                    foreach ($teaching as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            $teaching[$i] = '';
                        }
                    }
                }

                $countdisc = 1;
                foreach ($teaching as $i => $attr) {
                    $pos = strstr($i, 'discipline');
                    if ($pos) {
                        if (($teaching[$i] >= 99)) {
                            if ($countdisc == 1) {
                                $teaching[$i] = 99;
                            } else {
                                $teaching[$i] = '';
                            }
                            $countdisc++;
                        }
                    }
                }

                if ($teaching['role'] != '1' && $teaching['role'] != '5'&& $teaching['role'] != '6') {
                    $teaching['contract_type'] = '';
                }

                foreach ($teaching as $key => $attr) {
                    $alias = EdcensoAlias::model()->findByAttributes(['register' => '50', 'attr' => $key, 'year' => $year]);
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