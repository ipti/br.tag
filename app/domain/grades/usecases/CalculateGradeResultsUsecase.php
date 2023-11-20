<?php

/**
 * @property int $classroom
 * @property int $discipline
 */
class CalculateGradeResultsUsecase
{
    private const SITUATION_APPROVED = "Aprovado";
    private const SITUATION_DISPPROVED = "Reprovado";
    private const SITUATION_RECOVERY = "Recuperação";


    public function __construct($classroom, $discipline)
    {
        $this->classroom = $classroom;
        $this->discipline = $discipline;
    }

    public function exec()
    {
        $gradeUnities = $this->getUnitiesByClassroom($this->classroom);
        $studentEnrollments = StudentEnrollment::model()->findAll("classroom_fk = :classroom_fk", ["classroom_fk" => $this->classroom]);

        foreach ($studentEnrollments as $studentEnrollment) {
            $this->calculateGradesForStudent($studentEnrollment, $gradeUnities);
        }
    }

    private function calculateGradesForStudent($studentEnrollment, $gradeUnities)
    {
        $gradeResult = GradeResults::model()->find(
            "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
            [
                "enrollment_fk" => $studentEnrollment->id,
                "discipline_fk" => $this->discipline
            ]
        );

        $isNewGradeResult = $gradeResult == null;

        if ($isNewGradeResult) {
            $gradeResult = new GradeResults();
            $gradeResult->enrollment_fk = $studentEnrollment->id;
            $gradeResult->discipline_fk = $this->discipline;
        }

        if ($gradeUnities[0]->type != GradeUnity::TYPE_UNITY_BY_CONCEPT) {
            $this->calculateNumericGrades($studentEnrollment, $gradeUnities, $this->discipline);
        } else {
            $this->calculateConceptGrades($studentEnrollment, $gradeUnities, $this->discipline);
        }

        // Mudar status da matrícula
        // 1 = Matriculado; 6 = Aprovado; 8 = Reprovado
        if (
            $studentEnrollment->status == StudentEnrollment::STATUS_ACTIVE ||
            $studentEnrollment->status == StudentEnrollment::STATUS_APPROVED ||
            $studentEnrollment->status == StudentEnrollment::STATUS_DISAPPROVED
        ) {

            $this->changeEnrollmentStatus($studentEnrollment);
        }
    }

    private function getUnitiesByClassroom($classroom)
    {

        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->join = "join edcenso_stage_vs_modality esvm on gu.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->join .= " join classroom c on c.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->condition = "c.id = :classroom";
        $criteria->params = array(":classroom" => $classroom);

        return GradeUnity::model()->findAll($criteria);
    }

    private function changeEnrollmentStatus($studentEnrollment)
    {
        $allGradesFilled = true;
        $situation = self::SITUATION_APPROVED;
        foreach ($studentEnrollment->gradeResults as $gradeResult) {
            if ($gradeResult->situation == null) {
                $allGradesFilled = false;
            } elseif ($gradeResult->situation == self::SITUATION_DISPPROVED) {
                $situation = self::SITUATION_DISPPROVED;
                break;
            } elseif ($gradeResult->situation == self::SITUATION_RECOVERY) {
                $situation = "Matriculado";
                break;
            }
        }

        if ($allGradesFilled && $situation == self::SITUATION_APPROVED) {
            $studentEnrollment->status = StudentEnrollment::getStatusId(StudentEnrollment::STATUS_APPROVED);
        } elseif ($situation == self::SITUATION_DISPPROVED) {
            $studentEnrollment->status = StudentEnrollment::getStatusId(StudentEnrollment::STATUS_DISAPPROVED);
        } else {
            $studentEnrollment->status = StudentEnrollment::getStatusId(StudentEnrollment::STATUS_ACTIVE);
        }
        $studentEnrollment->save();
    }

    private function calculateNumericGrades($studentEnrollment, $gradeUnities, $discipline)
    {
        //notas sem ser conceito
        $arr = [];
        $arr["grades"] = [];
        foreach ($gradeUnities as $gradeUnity) {

            $unityRegister = ["unityId" => $gradeUnity->id, "unityGrade" => "", "gradeUnityType" => $gradeUnity->type];

            if ($gradeUnity->type == "UR") {
                $unityRegister["unityRecoverGrade"] = "";
            }

            array_push($arr["grades"], $unityRegister);
        }

        $unitiesByDiscipline = $this->getGradeUnitiesByDiscipline($discipline, $studentEnrollment->id);

        foreach ($unitiesByDiscipline as $gradeUnity) {
            $key = array_search($gradeUnity->id, array_column($arr["grades"], 'unityId'));
            $arr["grades"][$key] = $this->getUnidadeValues($gradeUnity, $studentEnrollment->id, $discipline);
        }
        $allNormalUnitiesFilled = true;
        $lastRSFilledGrade = "";
        $rfFilled = true;
        $this->calculateMedia();


    }


    private function getGradeUnitiesByDiscipline($discipline, $enrollmentId)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "distinct gu.id, gu.*";
        $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
        $criteria->join .= " join grade g on g.grade_unity_modality_fk = gum.id";
        $criteria->condition = "g.discipline_fk = :discipline_fk and enrollment_fk = :enrollment_fk";
        $criteria->params = array(":discipline_fk" => $discipline, ":enrollment_fk" => $enrollmentId);
        $criteria->order = "gu.id";
        $gradeUnitiesByDiscipline = GradeUnity::model()->findAll($criteria);

        return $gradeUnitiesByDiscipline;
    }

    private function calculateConceptGrades($studentEnrollment, $gradeUnities, $discipline)
    {
        //notas por conceito
        $index = 0;
        foreach ($gradeUnities as $gradeUnity) {
            foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
                $enrollmentId = $studentEnrollment->id;
                $studentGrades = array_filter(
                    $gradeUnityModality->grades,
                    function ($grade) use ($enrollmentId, $discipline) {
                        return $grade->enrollment_fk === $enrollmentId && $grade->discipline_fk === $discipline;
                    }
                );
                foreach ($studentGrades as $grade) {
                    $gradeResult["grade_concept_" . ($index + 1)] = $grade->gradeConceptFk->acronym;
                    $index++;
                }
            }
        }
        $gradeResult->situation = "Aprovado";
        $gradeResult->save();
    }

    private function getUnidadeValues($gradeUnity, $enrollmentId, $discipline)
    {
        $unityGrade = "";
        $unityRecoverGrade = "";
        $turnedEmptyToZero = false;
        $weightsSum = 0;
        $commonModalitiesCount = 0;

        foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
            if ($gradeUnityModality->type == "C") {
                $commonModalitiesCount++;
                $weightsSum += $gradeUnityModality->weight;
            }

            $studentGrades = array_filter(
                $gradeUnityModality->grades,
                function ($grade) use ($enrollmentId, $discipline) {
                    return $grade->enrollment_fk === $enrollmentId && $grade->discipline_fk === $discipline;
                }
            );

            foreach ($studentGrades as $grade) {
                if ($gradeUnityModality->type == "C") {
                    if (!$turnedEmptyToZero) {
                        $unityGrade = 0;
                        $turnedEmptyToZero = true;
                    }
                    $unityGrade += $gradeUnity->gradeCalculationFk->name === "Peso"
                        ? $grade->grade * $gradeUnityModality->weight
                        : $grade->grade;
                } else {
                    $unityRecoverGrade = (float) $grade->grade;
                }
            }
        }
        if ($unityGrade !== "") {
            if ($gradeUnity->gradeCalculationFk->name === "Média") {
                $unityGrade = number_format($unityGrade / $commonModalitiesCount, 2);
            } elseif ($gradeUnity->gradeCalculationFk->name === "Peso") {
                $unityGrade = number_format($unityGrade / $weightsSum, 2);
            }
        }
        return $gradeUnity->type == "UR"
            ? ["unityId" => $gradeUnity->id, "unityGrade" => $unityGrade, "unityRecoverGrade" => $unityRecoverGrade, "gradeUnityType" => $gradeUnity->type]
            : ["unityId" => $gradeUnity->id, "unityGrade" => $unityGrade, "gradeUnityType" => $gradeUnity->type];
    }

    private function calculateMedia(){
        //Cálculo da média final
        $arr = [];
        $arr["finalMedia"] = "";
        $sums = 0;
        $sumsCount = 0;
        $arr["semesterMedias"] = [];
        $hasRF = false;
        $recSemIndex = 0;
        $gradeIndex = 0;

        foreach ($arr["grades"] as $grade) {
            switch ($grade["gradeUnityType"]) {
                case "U":
                    if ($grade["unityGrade"] != "") {
                        $sums += $grade["unityGrade"];
                    } else {
                        $allNormalUnitiesFilled = false;
                    }
                    $sumsCount++;

                    $resultGradeResult = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;

                    $gradeResult["grade_" . ($gradeIndex + 1)] = $resultGradeResult <= 10.0 ? $resultGradeResult : 10.0;

                    $gradeIndex++;
                    break;
                case "UR":
                    if ($grade["unityGrade"] != "" || $grade["unityRecoverGrade"] != "") {
                        $sums += $grade["unityRecoverGrade"] > $grade["unityGrade"] ? $grade["unityRecoverGrade"] : $grade["unityGrade"];
                    } else {
                        $allNormalUnitiesFilled = false;
                    }
                    $sumsCount++;

                    $resultGradeResult = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;

                    $gradeResult["grade_" . ($gradeIndex + 1)] = $resultGradeResult <= 10.0 ? $resultGradeResult : 10.0;

                    $gradeResult["rec_bim_" . ($gradeIndex + 1)] = $grade["unityRecoverGrade"] != "" ? number_format($grade["unityRecoverGrade"], 1) : null;
                    $gradeIndex++;
                    break;
                case "RS":
                    if ($sums > 0) {
                        $semesterMedia = $sums / $sumsCount;
                        $semesterRecoverMedia = ($semesterMedia + $grade["unityGrade"]) / 2;
                        array_push($arr["semesterMedias"], $semesterMedia > $semesterRecoverMedia ? $semesterMedia : $semesterRecoverMedia);
                    } else {
                        array_push($arr["semesterMedias"], 0);
                    }
                    $sums = 0;
                    $sumsCount = 0;

                    $lastRSFilledGrade = $grade["unityGrade"];

                    $gradeResult["rec_sem_" . ($recSemIndex + 1)] = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;
                    $recSemIndex++;
                    break;
                case "RF":
                    $hasRF = true;
                    if ($sums > 0) {
                        $media = $sums / $sumsCount;
                        array_push($arr["semesterMedias"], $media);
                    }

                    $finalMedia = array_sum($arr["semesterMedias"]) / count($arr["semesterMedias"]);
                    if ($grade["unityGrade"] != "") {
                        $finalRecoverMedia = ($finalMedia + $grade["unityGrade"]) / 2;
                        $finalMedia = number_format($finalRecoverMedia, 1);
                    } else {
                        $finalMedia = number_format($finalMedia, 1);
                        $rfFilled = false;
                    }

                    $gradeResult["rec_final"] = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;
                    break;
            }
        }

        if (!$hasRF) {
            if ($sums > 0) {
                $media = $sums / $sumsCount;
                array_push($arr["semesterMedias"], $media);
            }
            $finalMedia = number_format(array_sum($arr["semesterMedias"]) / count($arr["semesterMedias"]), 1);
        }

        //traz a situação do aluno (se null, aprovado, recuperação ou reprovado)
        $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $gradeUnities[0]->edcenso_stage_vs_modality_fk]);
        $situation = null;
        if ($allNormalUnitiesFilled) {
            if ($finalMedia >= $gradeRules->approvation_media) {
                $situation = "Aprovado";
            } else {
                if ($hasRF) {
                    if ($rfFilled) {
                        if ($finalMedia >= $gradeRules->final_recover_media) {
                            $situation = "Aprovado";
                        } else {
                            $situation = "Reprovado";
                        }
                    } else {
                        $situation = self::SITUATION_RECOVERY;
                    }
                } elseif ($recSemIndex > 0) {
                    if ($lastRSFilledGrade !== "") {
                        $situation = "Reprovado";
                    } else {
                        $situation = self::SITUATION_RECOVERY;
                    }
                } else {
                    $situation = "Reprovado";
                }
            }
        }
        $gradeResult->situation = $situation;

        $gradeResult->final_media = $finalMedia;
        $gradeResult->save();

    }



}
