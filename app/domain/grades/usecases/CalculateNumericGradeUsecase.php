<?php

/**
 * @property int $classroomId
 * @property int $discipline
 */
class CalculateNumericGradeUsecase
{
    public function __construct($classroom, $discipline)
    {
        $this->classroomId = $classroom;
        $this->discipline = $discipline;
    }

    public function exec()
    {
        $classroom = Classroom::model()->with("activeStudentEnrollments.studentFk")->findByPk($this->classroomId);
        $studentEnrollments = $classroom->activeStudentEnrollments;
        $unitiesByDiscipline = $this->getGradeUnitiesByClassroomStage($this->classroomId);

        foreach ($studentEnrollments as $studentEnrollment) {
            $this->calculateNumericGrades($studentEnrollment, $this->discipline, $unitiesByDiscipline);
        }
    }

    private function calculateNumericGrades($studentEnrollment, $discipline, $unitiesByDiscipline)
    {
        $gradeResult = $this->getGradesResultForStudent($studentEnrollment->id, $discipline);
        foreach ($unitiesByDiscipline as $index => $gradeUnity) {
            if ($gradeUnity->type == GradeUnity::TYPE_UNITY) {
                $gradeResult = $this->calculateCommonUnity($gradeResult, $studentEnrollment, $discipline, $gradeUnity, $index);
            } elseif ($gradeUnity->type == GradeUnity::TYPE_UNITY_WITH_RECOVERY) {
                $gradeResult = $this->calculateUnityWithRecovery($gradeResult, $studentEnrollment, $discipline, $gradeUnity, $index);
            } elseif ($gradeUnity->type == GradeUnity::TYPE_SEMIANUAL_RECOVERY) {
                // @TODO: ALTERAR MEDOTO PARA CALCULO DA RECUPERACAO SEMESTRAL
                $gradeResult = $this->calculateSemiRecovery($gradeResult, $studentEnrollment, $discipline, $gradeUnity, $index);
            }
            } elseif ($gradeUnity->type == GradeUnity::TYPE_FINAL_RECOVERY) {
                $gradeResult = $this->calculateFinalRecovery($gradeResult, $studentEnrollment, $discipline, $gradeUnity);
            }
        }
        $gradeResult->setAttribute("final_media", null);
        $gradeResult->setAttribute("situation", null);
        $gradeResult->save();

        return $gradeResult;
    }

    private function calculateFinalRecovery($gradeResult, $studentEnrollment, $discipline, $unity)
    {
        $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $unity);
        $gradeResult->setAttribute("rec_final", is_nan($unityMedia) ? "" : $unityMedia);

        return $gradeResult;
    }

    private function calculateSemiRecovery($gradeResult, $studentEnrollment, $discipline, $unity)
    {
        $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $unity);
        $gradeResult->setAttribute("rec_semianual_1", is_nan($unityMedia) ? "" : $unityMedia);
        $gradeResult->setAttribute("rec_semianual_2", is_nan($unityMedia) ? "" : $unityMedia);

        return $gradeResult;
    }

    private function calculateCommonUnity($gradeResult, $studentEnrollment, $discipline, $unity, $index)
    {
        $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $unity);
        $gradeResult["grade_" . ($index + 1)] = is_nan($unityMedia) ? "" : round($unityMedia, 1);
        return $gradeResult;
    }

    private function calculateUnityWithRecovery($gradeResult, $studentEnrollment, $discipline, $unity, $index)
    {
        $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $unity);
        $recoveryGrade = $this->getRecoveryGradeFromUnity($studentEnrollment->id, $discipline, $unity->id);
        $gradeWithRecovery = [(float) $unityMedia, (float) $recoveryGrade->grade];
        $finalUnityMedia = max($gradeWithRecovery);
        $gradeResult["grade_" . ($index + 1)] = is_nan($finalUnityMedia) ? "" : $finalUnityMedia;
        return $gradeResult;
    }

    private function getRecoveryGradeFromUnity($enrollmentId, $discipline, $unityId)
    {

        $gradesIds = array_column(Yii::app()->db->createCommand(
            "SELECT
                g.id
            FROM grade g
                join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                join grade_unity gu on gu.id = gum.grade_unity_fk
            WHERE g.enrollment_fk = :enrollment_id
                and g.discipline_fk = :discipline_id
                and gu.id = :unity_id
                and gum.type = '" . GradeUnityModality::TYPE_RECOVERY . "'"
        )->bindParam(":enrollment_id", $enrollmentId)
            ->bindParam(":discipline_id", $discipline)
            ->bindParam(":unity_id", $unityId)->queryAll(), "id");

        if ($gradesIds == null) {
            return [];
        }

        return Grade::model()->find(
            array(
                'condition' => 'id =  :id',
                'params' => [":id" => $gradesIds[0]]
            )
        );
    }

    private function getGradeUnitiesByClassroomStage($classroom)
    {

        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->join = "join edcenso_stage_vs_modality esvm on gu.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->join .= " join classroom c on c.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->condition = "c.id = :classroom";
        $criteria->order = "gu.type desc";
        $criteria->params = array(":classroom" => $classroom);

        return GradeUnity::model()->findAll($criteria);
    }


    /**
     * @param int $studentEnrollmentId
     * @param int $studentEnrollmentId
     *
     * @return GradeResults
     */
    private function getGradesResultForStudent($studentEnrollmentId, $disciplineId)
    {
        $gradeResult = GradeResults::model()->find(
            "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
            [
                "enrollment_fk" => $studentEnrollmentId,
                "discipline_fk" => $disciplineId
            ]
        );

        $isNewGradeResult = $gradeResult == null;

        if ($isNewGradeResult) {
            $gradeResult = new GradeResults();
            $gradeResult->enrollment_fk = $studentEnrollmentId;
            $gradeResult->discipline_fk = $disciplineId;
        }

        return $gradeResult;
    }

    /**
     * @param StudentEnrollment $enrollment
     * @param int $discipline
     * @param GradeUnity $unity
     *
     * @return float|null
     */
    private function calculateUnityMedia($enrollment, $disciplineId, $unity)
    {

        $grades = $this->getStudentGradesFromUnity(
            $enrollment->id,
            $disciplineId,
            $unity->id
        );

        return $this->applyStrategyComputeGradesByFormula($unity, array_column($grades, "grade"));
    }

    /**
     * @param GradeUnity $unity
     * @param float[] $grades
     */
    private function applyStrategyComputeGradesByFormula($unity, $grades)
    {
        $result = 0;
        switch ($unity->gradeCalculationFk->name) {
            default:
            case 'Soma':
                $result = array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                break;
            case 'Média':
                $finalGrade = array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                $result = round($finalGrade / sizeof($grades), 2);
                break;
            case 'Maior':
                $result = max($grades);
                break;
            case 'Menor':
                $result = min($grades);
                break;
            case 'Peso':
                $acc = [0, 0];
                $modalities = $unity->gradeUnityModalities;

                foreach ($grades as $key => $grade) {
                    $acc[0] += $grade * $modalities[$key]->weight;
                    $acc[1] += $modalities[$key]->weight;
                }

                $result = $acc[0] / $acc[1];
                break;
        }

        return $result;
    }


    /**
     * @param int $enrollmentId
     * @param int $discipline
     * @param int $unityId
     *
     * @return Grade[]
     */
    private function getStudentGradesFromUnity($enrollmentId, $discipline, $unityId)
    {

        $gradesIds = array_column(Yii::app()->db->createCommand(
            "SELECT
                g.id
                FROM grade g
                join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                join grade_unity gu on gu.id= gum.grade_unity_fk
                WHERE g.enrollment_fk = :enrollment_id and g.discipline_fk = :discipline_id and gu.id = :unity_id and gum.type = '" . GradeUnityModality::TYPE_COMMON . "'"
        )->bindParam(":enrollment_id", $enrollmentId)
            ->bindParam(":discipline_id", $discipline)
            ->bindParam(":unity_id", $unityId)->queryAll(), "id");

        if ($gradesIds == null) {
            return [];
        }

        return Grade::model()->findAll(
            array(
                'condition' => 'id IN (' . implode(',', $gradesIds) . ')',
            )
        );

    }

}
