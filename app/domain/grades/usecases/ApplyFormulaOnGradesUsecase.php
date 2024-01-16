<?php

/**
 * @property GradeCalculation $calculation
 * @property float[] $grades
 * @property int[] $weights
 */
class ApplyFormulaOnGradesUsecase
{
    public function __construct($calculation, $weights = [])
    {
        $this->calculation = $calculation;
        $this->weights = $weights;
    }

    public function setGrades($grades){
        $this->grades = $grades;
        return $this;
    }

    public function setWeights($weights){
        $this->weights = $weights;
        return $this;
    }

    public function exec()
    {
        if(empty($this->grades)){
            throw new Exception("Não foram adicionadas notas antes de excutar o caso de uso", 1);
        }

        $result = 0;
        switch ($this->calculation->id) {
            default:
            case GradeCalculation::OP_SUM:
                $result = array_reduce($this->grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                break;
            case GradeCalculation::OP_MAX:
                $result = max($this->grades);
                break;
            case GradeCalculation::OP_MIN:
                $result = min($this->grades);
                break;
            case GradeCalculation::OP_MEDIA:
                $finalGrade = array_reduce($this->grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                $result = $finalGrade / sizeof($this->grades);
                break;
            case GradeCalculation::OP_MEDIA_BY_WEIGTH:
                $acc = [0, 0];
                foreach ($this->grades as $key => $value) {
                    $acc[0] += $value * $this->weights[$key];
                    $acc[1] += $this->weights[$key];
                }

                $result = $acc[0] / $acc[1];
                break;
        }

        return round($result, 1);
    }

}
