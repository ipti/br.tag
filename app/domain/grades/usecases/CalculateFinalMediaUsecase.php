<?php

/**
 * @property GradeResults $gradesResult
 * @property GradeRules $gradeRule
 * @property GradeUnity[] $gradesStudent
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
        $grades = [];
        if($this->gradeRule->gradeCalculationFk->name == 'Média Semestral') {
            $semRecPartial1 = is_numeric($this->gradesResult["sem_rec_partial_1"]) ? $this->gradesResult["sem_rec_partial_1"] : 0;
            $semRecPartial2 = is_numeric($this->gradesResult["sem_rec_partial_2"]) ? $this->gradesResult["sem_rec_partial_2"] : 0;

            $gradesSemAvarage1 =  max($this->gradesResult["sem_avarage_1"], $semRecPartial1);
            $gradesSemAvarage2 =  max($this->gradesResult["sem_avarage_2"], $semRecPartial2);

            $grades = [$gradesSemAvarage1, $gradesSemAvarage2];
            $calculation = GradeCalculation::model()->findByAttributes(["name"=>"Média"]);
            $finalMedia = $this->applyCalculation($calculation, $grades);
        } else {
            $grades = $this->extractGrades($this->gradesResult, $this->countUnities);
            $finalMedia = $this->applyCalculation($this->gradeRule->gradeCalculationFk, $grades);
        }

        if ($this->shouldApplyFinalRecovery($this->gradeRule, $finalMedia)) {
            $gradesFinalRecovery = [];

            if ($this->gradeRule->gradeCalculationFk->name == 'Média Semestral') {
                // Verifica se os valores são números antes de comparar
                $semRecPartial1 = is_numeric($this->gradesResult["sem_rec_partial_1"]) ? $this->gradesResult["sem_rec_partial_1"] : 0;
                $semRecPartial2 = is_numeric($this->gradesResult["sem_rec_partial_2"]) ? $this->gradesResult["sem_rec_partial_2"] : 0;

                $gradesSemAvarage1 =  max($this->gradesResult["sem_avarage_1"], $semRecPartial1);
                $gradesSemAvarage2 =  max($this->gradesResult["sem_avarage_2"], $semRecPartial2);

                $gradesFinalRecovery = [$gradesSemAvarage1, $gradesSemAvarage2];
            } else {
                $gradesFinalRecovery[] = $finalMedia;
            }

            $finalMedia = $this->applyFinalRecovery($this->gradesResult, $gradesFinalRecovery);
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

    private function applyFinalRecovery($gradesResult, $gradesFinalRecovery)
    {
        $result = null;
        array_push($gradesFinalRecovery, $gradesResult->rec_final);
        $finalRecovery = $this->getFinalRevovery($gradesResult->enrollment_fk, $gradesResult->discipline_fk);
        if($finalRecovery->gradeCalculationFk->name == "Média Semestral")
        {
            $calculation = GradeCalculation::model()->findByAttributes(["name"=>"Média"]);
            $result = $this->applyCalculation($calculation, $gradesFinalRecovery);
        } else
        {
            $result = $this->applyCalculation($finalRecovery->gradeCalculationFk, $gradesFinalRecovery);
        }
        return $result;
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
                $gradePartialRecovery =$gradesResult->attributes["rec_partial_" . $this->gradesStudent[$i]->parcialRecoveryFk->order_partial_recovery];


                $grade = $grade < $gradePartialRecovery  ? $gradePartialRecovery : $grade;
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
