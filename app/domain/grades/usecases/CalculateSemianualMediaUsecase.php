<?php

/**
 * @property GradeResults $gradesResult
 * @property GradeRules $gradeRule
 * @property int $countUnities
 */
class CalculateSemianualMediaUsecase
{
    public function __construct($gradesResult, $gradeRule, $countUnities)
    {
        $this->gradesResult = $gradesResult;
        $this->gradeRule = $gradeRule;
        $this->countUnities = $countUnities;
    }

    public function exec()
    {
        $semiGrade = 

        $grades = $this->extractGrades($this->gradesResult, $this->countUnities);
        $semianualMedia = $this->applyCalculation($this->gradeRule->gradeCalculationFk, $grades);

        if ($this->shouldApplySemianualRecovery($this->gradesResult, $semianualMedia)) {
            $this->applySemianualRecovery($this->gradesResult, $semianualMedia);
        }

        $this->saveSemianualMedia($this->gradesResult, $semianualMedia);
    }

    private function saveSemianualMedia($gradesResult, $semianualMedia)
    {
        $gradesResult->setAttribute("semianual_media", $semianualMedia);
        $gradesResult->save();
    }

    private function shouldApplySemianualRecovery($gradeRule, $semianualMedia)
    {
        return isset($gradeRule->has_semianual_recovery)
            && $gradeRule->has_semianual_recovery
            && $semianualMedia < (double) $gradeRule->semi_recover_media;

    }

    private function applySemianualRecovery($gradesResult, $semianualMedia)
    {
        $semianualRecovery = $this->getSemianualRecovery($gradesResult->enrollment_fk, $gradesResult->discipline_fk);
        return $this->applyCalculation($semianualRecovery->gradeCalculationFk, [$semianualMedia, $gradesResult->semianual_media]);
    }

    private function getSemianualRecovery($enrollmentId, $discipline)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->select = "distinct gu.id, gu.*";
        $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
        $criteria->join .= " join grade g on g.grade_unity_modality_fk = gum.id";
        $criteria->condition = "g.discipline_fk = :discipline_fk and enrollment_fk = :enrollment_fk and gu.type = :type";
        $criteria->params = array(":discipline_fk" => $discipline, ":enrollment_fk" => $enrollmentId, ":type" => GradeUnity::TYPE_SEMIANUAL_RECOVERY);
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
            array_push($grades, $gradesResult->attributes["grade_" . ($i + 1)]);
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
