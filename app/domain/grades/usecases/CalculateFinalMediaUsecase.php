<?php

/**
 * @property int $enrollmentId
 * @property int $disciplineId
 */
class CalculateFinalMediaUsecase
{
    public function __construct($enrollmentId, $disciplineId)
    {
        $this->enrollmentId = $enrollmentId;
        $this->disciplineId = $disciplineId;
    }

    public function exec()
    {

        $unities = $this->getGradeUnitiesByDiscipline($this->enrollmentId, $this->disciplineId);

        /** @var GradeResults  $gradesResult */
        $gradesResult = GradeResults::model()->findByAttributes(array('enrollment_fk' => $this->enrollmentId, 'discipline_fk' => $this->disciplineId));

        /** @var GradeRules  $gradeRule */
        $gradeRule = GradeRules::model()->find(
            "edcenso_stage_vs_modality_fk = :stage",
            [
                ":stage" => $gradesResult->enrollmentFk->classroomFk->edcenso_stage_vs_modality_fk
            ]
        );



        $countUnities = count($unities);

        $grades = [];
        for ($i = 0; $i < $countUnities; $i++) {
            array_push($grades, $gradesResult->attributes["grade_" . ($i + 1)]);
        }

        $finalMedia = $this->applyStrategyComputeGradesByFormula($gradeRule->gradeCalculationFk, $grades);

        if(isset($gradeRule->has_final_recovery) && $gradeRule->has_final_recovery &&  $finalMedia < (double)$gradeRule->approvation_media) {
            $finalRecovery = $this->getFinalRevovery($this->enrollmentId, $this->disciplineId);
            $finalMedia = $this->applyStrategyComputeGradesByFormula($finalRecovery->gradeCalculationFk, [$finalMedia, $gradesResult->rec_final]);
        }

        $gradesResult->setAttribute("final_media", $finalMedia);
        $gradesResult->save();
    }

    private function getGradeUnitiesByDiscipline($enrollmentId, $discipline)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->select = "distinct gu.id, gu.*";
        $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
        $criteria->join .= " join grade g on g.grade_unity_modality_fk = gum.id";
        $criteria->condition = "g.discipline_fk = :discipline_fk and enrollment_fk = :enrollment_fk and gu.type = :type";
        $criteria->params = array(":discipline_fk" => $discipline, ":enrollment_fk" => $enrollmentId, ":type" => GradeUnity::TYPE_UNITY);
        $criteria->order = "gu.id";
        return GradeUnity::model()->findAll($criteria);
    }

    private function getFinalRevovery($enrollmentId, $discipline)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->select = "distinct gu.id, gu.*";
        $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
        $criteria->join .= " join grade g on g.grade_unity_modality_fk = gum.id";
        $criteria->condition = "g.discipline_fk = :discipline_fk and enrollment_fk = :enrollment_fk and gu.type = :type";
        $criteria->params = array(":discipline_fk" => $discipline, ":enrollment_fk" => $enrollmentId, ":type" => GradeUnity::TYPE_FINAL_RECOVERY);
        $criteria->order = "gu.id";
        return GradeUnity::model()->find($criteria);
    }


    /**
     * @param int $studentEnrollmentId
     * @param int $disciplineId
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
     * @param GradeCalculation $calculation
     * @param float[] $grades
     * @param int[] $weights
     */
    private function applyStrategyComputeGradesByFormula($calculation, $grades, $weights = [])
    {
        switch ($calculation->id) {
            default:
            case GradeCalculation::OP_SUM:
                return array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
            case GradeCalculation::OP_MAX:
                return max($grades);
            case GradeCalculation::OP_MIN:
                return min($grades);
            case GradeCalculation::OP_MEDIA:
                $finalGrade = array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                return round($finalGrade / sizeof($grades), 2);
            case GradeCalculation::OP_MEDIA_BY_WEIGTH:
                $acc = [0, 0];
                foreach ($grades as $key => $value) {
                    $acc[0] += $value * $weights[$key];
                    $acc[1] += $weights[$key];
                }

                return $acc[0] / $acc[1];
        }
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
                WHERE g.enrollment_fk = :enrollment_id and g.discipline_fk = :discipline_id and gu.id = :unity_id"
        )->bindParam(":enrollment_id", $enrollmentId)
            ->bindParam(":discipline_id", $discipline)
            ->bindParam(":unity_id", $unityId)->queryAll(), "id");


        $grades = Grade::model()->findAll(
            array(
                'condition' => 'id IN (' . implode(',', $gradesIds) . ')',
            )
        );

        return $grades;
    }

}
