<?php

/**
 * @property int $classroom
 * @property int $discipline
 */
class CalculateGradeResultsUsecase
{
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
        if ($gradeUnities[0]->type != GradeUnity::TYPE_UNITY_BY_CONCEPT) {
            $this->calculateNumericGrades($studentEnrollment, $this->discipline);
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

    private function calculateNumericGrades($studentEnrollment, $discipline)
    {
        $unitiesByDiscipline = $this->getGradeUnitiesByDiscipline($discipline, $studentEnrollment->id);
        $gradeResult = $this->getGradesResultForStudent($studentEnrollment->id, $discipline);

        foreach ($unitiesByDiscipline as $index => $gradeUnity) {
            if ($gradeUnity->type == GradeUnity::TYPE_UNITY) {
                $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $gradeUnity);
                $gradeResult->setAttribute("grade_" . ($index + 1), $unityMedia);
            } elseif ($gradeUnity->type == GradeUnity::TYPE_FINAL_RECOVERY) {
                $gradeResult = $this->calculateFinalRecovery($studentEnrollment, $discipline, $gradeUnity);
            }
        }

        $gradeResult->save();

        return $gradeResult;
    }

    private function calculateFinalRecovery($studentEnrollment, $discipline, $unity)
    {
        $gradeResult = $this->getGradesResultForStudent($studentEnrollment->id, $discipline);
        $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $unity);
        $gradeResult->setAttribute("rec_final", strval($unityMedia));

        return $gradeResult;
    }

    private function getGradeUnitiesByDiscipline($discipline, $enrollmentId)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->select = "distinct gu.id, gu.*";
        $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
        $criteria->join .= " join grade g on g.grade_unity_modality_fk = gum.id";
        $criteria->condition = "g.discipline_fk = :discipline_fk and enrollment_fk = :enrollment_fk";
        $criteria->params = array(":discipline_fk" => $discipline, ":enrollment_fk" => $enrollmentId);
        $criteria->order = "gu.id";
        $gradeUnitiesByDiscipline = GradeUnity::model()->findAll($criteria);

        return $gradeUnitiesByDiscipline;
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

    // private function calculateConceptGrades($studentEnrollment, $gradeUnities, $discipline)
    // {
    //     //notas por conceito
    //     $index = 0;
    //     foreach ($gradeUnities as $gradeUnity) {
    //         foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
    //             $enrollmentId = $studentEnrollment->id;
    //             $studentGrades = array_filter(
    //                 $gradeUnityModality->grades,
    //                 function ($grade) use ($enrollmentId, $discipline) {
    //                     return $grade->enrollment_fk === $enrollmentId && $grade->discipline_fk === $discipline;
    //                 }
    //             );
    //             foreach ($studentGrades as $grade) {
    //                 $gradeResult["grade_concept_" . ($index + 1)] = $grade->gradeConceptFk->acronym;
    //                 $index++;
    //             }
    //         }
    //     }
    //     $gradeResult->situation = "Aprovado";
    //     $gradeResult->save();
    // }

    /**
     * @param StudentEnrollment $enrollment
     * @param int $discipline
     * @param GradeUnity $unity
     *
     * @return float
     */
    private function calculateUnityMedia($enrollment, $discipline, $unity)
    {
        $grades = $this->getStudentGradesFromUnity(
            $enrollment->id,
            $discipline,
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

        switch ($unity->gradeCalculationFk->name) {
            default:
            case 'Soma':
                return array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
            case 'Média':
                $finalGrade = array_reduce($grades, function ($acc, $grade) {
                    /** @var Grade $grade */
                    $acc += floatval($grade);
                    return $acc;
                });
                return round($finalGrade / sizeof($grades), 2);
            case 'Peso':
                $acc = [0, 0];
                $modalities = $unity->gradeUnityModalities;

                foreach ($grades as $key => $grade) {
                    $acc[0] += $grade * $modalities[$key]->weight;
                    $acc[1] += $modalities[$key]->weight;
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
