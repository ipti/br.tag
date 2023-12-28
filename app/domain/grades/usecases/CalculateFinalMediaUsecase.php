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

        if (isset($gradeRule->has_final_recovery) && $gradeRule->has_final_recovery && $finalMedia < (double) $gradeRule->approvation_media) {
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
     * @param GradeCalculation $calculation
     * @param float[] $grades
     * @param int[] $weights
     */
    private function applyStrategyComputeGradesByFormula($calculation, $grades, $weights = [])
    {
        $result = 0;
        switch ($calculation->id) {
            default:
            case GradeCalculation::OP_SUM:
                $result = array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                break;
            case GradeCalculation::OP_MAX:
                $result = max($grades);
                break;
            case GradeCalculation::OP_MIN:
                $result = min($grades);
                break;
            case GradeCalculation::OP_MEDIA:
                $finalGrade = array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                $result = round($finalGrade / sizeof($grades), 2);
                break;
            case GradeCalculation::OP_MEDIA_BY_WEIGTH:
                $acc = [0, 0];
                foreach ($grades as $key => $value) {
                    $acc[0] += $value * $weights[$key];
                    $acc[1] += $weights[$key];
                }

                $result = $acc[0] / $acc[1];
                break;
        }

        return $result;
    }

}
