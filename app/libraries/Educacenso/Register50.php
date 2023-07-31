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
                $instructors[$teachingData->instructor_fk]['teaching'][$classroom->id]["disciplines"] = [];
                foreach ($teachingData->teachingMatrixes as $teachingMatrix) {
                    array_push($instructors[$teachingData->instructor_fk]['teaching'][$classroom->id]["disciplines"], $teachingMatrix->curricularMatrixFk->discipline_fk);
                }
            }
        }

        foreach ($instructors as $instructor) {
            $id = (String)'II' . $instructor['identification']['id'];

            foreach ($instructor['teaching'] as $teaching) {
                $register = [];
                
                // ignora professor de apoio
                if($teaching['role'] == '8'){
                    continue;
                }

                $teaching['register_type'] = '50';
                $teaching['instructor_fk'] = $id;

                $classroom = Classroom::model()->findByPk($teaching['classroom_id_fk']);

                $classroom->edcenso_stage_vs_modality_fk = $classroom->edcenso_stage_vs_modality_fk % 10000;
                
                $codigos = [];
                $alreadyHave99 = false;
                $n = 0;
                for ($i = 9; $i <= 33; $i++) {
                    if ($classroom->edcenso_stage_vs_modality_fk == 1 || $classroom->edcenso_stage_vs_modality_fk == 2 || $classroom->edcenso_stage_vs_modality_fk == 3
                        || ($teaching["role"] != '1' && $teaching["role"] != '5')) {
                        $codigos[$i] = "";
                    } else {
                        $value = $teaching["disciplines"][$n] != null ? $teaching["disciplines"][$n] : "";
                        if ($value >= 99 || $value == 20 || $value == 21) {
                            if (!$alreadyHave99) {
                                $codigos[$i] = 99;
                                $alreadyHave99 = true;
                            } else {
                                $codigos[$i] = "";
                            }
                        } else {
                            $codigos[$i] = $value;
                        }
                        $n++;
                    }
                }

                if ($classroom->pedagogical_mediation_type == "1" && ($teaching['role'] != '1' && $teaching['role'] != '2' && $teaching['role'] != '3' && $teaching['role'] != '4')) {
                    $teaching['role'] = "1";
                } else if ($classroom->pedagogical_mediation_type == "3" && ($teaching['role'] != '4' && $teaching['role'] != '5')) {
                    $teaching['role'] = "4";
                }

                if ($teaching['role'] != '1' && $teaching['role'] != '5' && $teaching['role'] != '6') {
                    $teaching['contract_type'] = '';
                } else if ($teaching['contract_type'] == '') {
                    $teaching['contract_type'] = '1';
                }

                $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 50 order by corder', [":year" => $year]);
                foreach ($edcensoAliases as $edcensoAlias) {
                    $register[$edcensoAlias->corder] = $edcensoAlias->default;
                    //códigos
                    if ($edcensoAlias->corder >= 9 && $edcensoAlias->corder <= 33) {
                        $register[$edcensoAlias->corder] = $codigos[$edcensoAlias->corder];
                    }
                    else if ($edcensoAlias["attr"] != null && $teaching[$edcensoAlias["attr"]] !== $edcensoAlias->default) {
                        $register[$edcensoAlias->corder] = $teaching[$edcensoAlias["attr"]];
                    }

                    if ($classroom->aee == 1 && $edcensoAlias->corder >= 9 && $edcensoAlias->corder <= 41   ){
                        $register[$edcensoAlias->corder] = '';
                    }
                }
                
                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }
}