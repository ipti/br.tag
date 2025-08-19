<?php

/**
 * @property GradeResults $gradesResult
 * @property GradeRules $gradeRule
 * @property GradeUnity[] $gradesStudent
 * @property int $countUnities
 */
class CalculateFinalMediaUsecase
{
    private $gradesResult;
    private $gradeRule;
    private $gradesStudent;
    private $countUnities;

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
            if ($this->gradeRule->gradeCalculationFk->name == 'Média Semestral') {
                $semRecPartial1 = is_numeric($this->gradesResult["sem_rec_partial_1"]) ? $this->gradesResult["sem_rec_partial_1"] : 0;
                $semRecPartial2 = is_numeric($this->gradesResult["sem_rec_partial_2"]) ? $this->gradesResult["sem_rec_partial_2"] : 0;

                $gradesSemAvarage1 = max($this->gradesResult["sem_avarage_1"], $semRecPartial1);
                $gradesSemAvarage2 = max($this->gradesResult["sem_avarage_2"], $semRecPartial2);

                if ($gradesSemAvarage1 !== null) {
                    $grades[] = $gradesSemAvarage1;
                }

                if ($gradesSemAvarage2 !== null) {
                    $grades[] = $gradesSemAvarage2;
                }

                $calculation = GradeCalculation::model()->findByAttributes(["name"=>"Média"]);
                $finalMedia = $this->applyCalculation($calculation, $grades);
            } else {
                $grades = $this->extractGrades($this->gradesResult, $this->countUnities);
                $finalMedia = $this->applyCalculation($this->gradeRule->gradeCalculationFk, $grades);
            }

            $this->saveFinalMedia($this->gradesResult, $finalMedia);
            if ($this->shouldApplyFinalRecovery($this->gradeRule, $finalMedia)) {

                $gradeUnity = GradeUnity::model()->findByAttributes(
                    ["grade_rules_fk" => $this->gradeRule->id,
                    "type" =>  "RF"]);

                $gradesFinalRecovery = [];



                if ($gradeUnity->gradeCalculationFk->name == 'Média Semestral' && $gradeUnity->final_recovery_avarage_formula == "Médias dos Semestres") {
                    // Verifica se os valores são números antes de comparar
                    $semRecPartial1 = is_numeric($this->gradesResult["sem_rec_partial_1"]) ? $this->gradesResult["sem_rec_partial_1"] : 0;
                    $semRecPartial2 = is_numeric($this->gradesResult["sem_rec_partial_2"]) ? $this->gradesResult["sem_rec_partial_2"] : 0;

                    $gradesSemAvarage1 = max($this->gradesResult["sem_avarage_1"], $semRecPartial1);
                    $gradesSemAvarage2 = max($this->gradesResult["sem_avarage_2"], $semRecPartial2);

                    $gradesFinalRecovery = [];

                    if ($gradesSemAvarage1 !== null) {
                        $gradesFinalRecovery[] = $gradesSemAvarage1;
                    }

                    if ($gradesSemAvarage2 !== null) {
                        $gradesFinalRecovery[] = $gradesSemAvarage2;
                    }

                }
                else {
                    $gradesFinalRecovery[] = $finalMedia;
                }


                $finalMedia = $this->applyFinalRecovery($this->gradesResult, $gradesFinalRecovery);
                $this->saveFinalRecoveryMedia($this->gradesResult, $finalMedia);
            }
            TLog::info("Média final calculada", ["finalMedia" => $finalMedia]);
    }

    private function saveFinalMedia($gradesResult, $finalMedia)
    {
        $gradesResult->setAttribute("final_media", $finalMedia);
        $gradesResult->save();
    }
    private function saveFinalRecoveryMedia($gradesResult, $finalMedia)
    {
        $gradesResult->setAttribute("rec_final", $finalMedia);
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
        $finalRecovery = GradeUnity::model()->findByAttributes(["grade_rules_fk"=> $this->gradeRule->id, "type"=>"RF"]);
        $finalRecoveryGrade = $this->getFinalRevoveryGrade($gradesResult->enrollment_fk, $gradesResult->discipline_fk, $finalRecovery->id);
        array_push($gradesFinalRecovery, $finalRecoveryGrade);
        if ($finalRecovery->gradeCalculationFk->name == "Média Semestral") {

            $calculation = GradeCalculation::model()->findByAttributes(["name" => "Média"]);
            $result = $this->applyCalculation($calculation, $gradesFinalRecovery);
        } elseif ($finalRecovery->gradeCalculationFk->name == "Peso")
        {
            $weights = [
                $finalRecovery->weight_final_recovery,
                $finalRecovery->weight_final_media

            ];
            $result = $this->applyCalculation($finalRecovery->gradeCalculationFk, $gradesFinalRecovery, $weights);
        }
         else
        {
            $result = $this->applyCalculation($finalRecovery->gradeCalculationFk, $gradesFinalRecovery);
        }
        return $result;
    }


    private function getFinalRevoveryGrade($enrollmentId, $discipline, $finalRecoveryId)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "g";
        $criteria->select = "distinct g.id, g.*";
        $criteria->join = " join grade_unity_modality gum1 on g.grade_unity_modality_fk = gum1.id";
        $criteria->join .= " join grade_unity gu on gum1.grade_unity_fk = gu.id"; // Corrigido o alias e referência
        $criteria->condition = "g.discipline_fk = :discipline_fk and g.enrollment_fk = :enrollment_fk and gu.id = :finalRecoveryId";
        $criteria->params = array(
            ":discipline_fk" => $discipline,
            ":enrollment_fk" => $enrollmentId,
            ":finalRecoveryId" => $finalRecoveryId
        );
        $criteria->order = "g.id";
        return Grade::model()->find($criteria)->grade;

    }

    private function applyCalculation($calculation, $grades, $weights = [])
    {
        return (new ApplyFormulaOnGradesUsecase($calculation))
            ->setGrades($grades)
            ->setWeights($weights)
            ->exec();
    }

    private function extractGrades($gradesResult, $countUnities)
    {
        $grades = [];
        for ($i = 0; $i < $countUnities; $i++) {
            $grade = $gradesResult->attributes["grade_" . ($i + 1)];

            if ($this->gradesStudent[$i]->parcialRecoveryFk !== null) {
                $gradePartialRecovery = $gradesResult->attributes["rec_partial_" . $this->gradesStudent[$i]->parcialRecoveryFk->order_partial_recovery];


                $grade = $grade < $gradePartialRecovery ? $gradePartialRecovery : $grade;
            }
            array_push($grades, $grade);
        }
        return $grades;
    }
}
