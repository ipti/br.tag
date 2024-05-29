<?php

/**
 * @property GradeResults $gradesResult
 * @property GradeRules $gradeRule
 * @property GradeUnity[] $gradesStudant
 * @property int $countUnities
 */
class CalculateFinalMediaUsecase
{
    public function __construct($gradesResult, $gradeRule, $countUnities, $gradesStudent = null)
    {
        $this->gradesResult = $gradesResult;
        $this->gradeRule = $gradeRule;
        $this->countUnities = $countUnities;
        $this->gradesStudent = $gradesStudent;
    }

    public function exec()
    {
        $grades = $this->extractGrades($this->gradesResult, $this->countUnities);
        $finalMedia = $this->applyCalculation($this->gradeRule->gradeCalculationFk, $grades);

        if ($this->shouldApplyFinalRecovery($this->gradesResult, $finalMedia)) {
            $this->applyFinalRecovery($this->gradesResult, $finalMedia);
        }

        $this->saveFinalMedia($this->gradesResult, $finalMedia);
    }

    private function saveFinalMedia($gradesResult, $finalMedia)
    {
        $gradesResult->setAttribute("final_media", $finalMedia);
        $gradesResult->save();
    }

    private function shouldApplyFinalRecovery($gradeRule, $finalMedia)
    {
        return isset($gradeRule->has_final_recovery)
            && $gradeRule->has_final_recovery
            && $finalMedia < (double) $gradeRule->approvation_media;

    }

    private function applyFinalRecovery($gradesResult, $finalMedia)
    {
        $finalRecovery = $this->getFinalRevovery($gradesResult->enrollment_fk, $gradesResult->discipline_fk);
        return $this->applyCalculation($finalRecovery->gradeCalculationFk, [$finalMedia, $gradesResult->rec_final]);
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

    private function applyCalculation($calculation, $grades)
    {
        return (new ApplyFormulaOnGradesUsecase($calculation))
            ->setGrades($grades)
            ->exec();
    }

    private function extractGrades($gradesResult, $countUnities)
    {
        $grades = [];
        for ($i = 0; $i < $countUnities; $i++) {
            $grade = $gradesResult->attributes["grade_" . ($i + 1)];

            if($this->gradesStudent[$i]->parcialRecoveryFk !== null)
            {
                $gradePartialRecovery = $this->gradesStudent[$i]->parcialRecoveryFk;

                $grade = $grade < $gradesResult->attributes["rec_partial_" . $gradePartialRecovery->order_partial_recovery];
            }
            array_push($grades, $grade);
        }
        return $grades;
    }

    private function getUnitiesCount()
    {
        return (
            new GetGradeUnitiesByDisciplineUsecase(
                $this->gradeRule->edcenso_stage_vs_modality_fk
            )
        )->execCount();
    }
}
