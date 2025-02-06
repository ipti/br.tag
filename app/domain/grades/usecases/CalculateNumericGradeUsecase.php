<?php

/**
 * @property int $classroomId
 * @property int $stage
 * @property int $discipline
 */
class CalculateNumericGradeUsecase
{
    public function __construct($classroom, $discipline, $stage)
    {
        $this->classroomId = $classroom;
        $this->discipline = $discipline;
        $this->stage = $stage;
    }

    public function exec()
    {
        $classroom = Classroom::model()->with("activeStudentEnrollments.studentFk")->findByPk($this->classroomId);
        $totalEnrollments = $classroom->activeStudentEnrollments;
        if($classroom->edcenso_stage_vs_modality_fk !== $this->stage){
            $totalEnrollments = $classroom->activeStudentEnrollments;
            $studentEnrollments = [];
            foreach ($totalEnrollments as $enrollment) {
                if($enrollment->edcenso_stage_vs_modality_fk == $this->stage){
                    array_push($studentEnrollments, $enrollment);
                }
            }
        } else {
            $studentEnrollments = $totalEnrollments;
        }
        $gradeUnities = new GetGradeUnitiesByDisciplineUsecase($this->classroomId, $this->stage);
        $unitiesByDiscipline = $gradeUnities->exec();

        foreach ($studentEnrollments as $studentEnrollment) {
            $this->calculateNumericGrades($studentEnrollment, $this->discipline, $unitiesByDiscipline);
        }
    }

    private function calculateNumericGrades($studentEnrollment, $discipline, $unitiesByDiscipline)
    {
        $gradeResult = $this->getGradesResultForStudent($studentEnrollment->id, $discipline);
        $gradesRecoveries = [];
        $semAvarage1 = null;
        $unitiesSem1 = 0;
        $semAvarage2 = null;
        $unitiesSem2 = 0;
        foreach ($unitiesByDiscipline as $index => $gradeUnity) {
            if ($gradeUnity->type == GradeUnity::TYPE_UNITY || $gradeUnity->type == GradeUnity::TYPE_UNITY_WITH_RECOVERY) {
                $gradeResult = ($gradeUnity->type == GradeUnity::TYPE_UNITY) ?
                    $this->calculateCommonUnity($gradeResult, $studentEnrollment, $discipline, $gradeUnity, $index) :
                    $this->calculateUnityWithRecovery($gradeResult, $studentEnrollment, $discipline, $gradeUnity, $index);

                $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $gradeUnity);
                $unityMedia = is_nan($unityMedia ?? NAN) ? 0 : round($unityMedia, 1);

                if ($gradeUnity->type == GradeUnity::TYPE_UNITY_WITH_RECOVERY) {
                    $recoveryGrade = $this->getRecoveryGradeFromUnity($studentEnrollment->id, $discipline, $gradeUnity->id)->grade;
                    $unityMedia = max($unityMedia, (float) $recoveryGrade);
                }

                if ($gradeUnity->semester == 1) {
                    $semAvarage1 += $unityMedia;
                    $unitiesSem1++;
                } elseif ($gradeUnity->semester == 2) {
                    $semAvarage2 += $unityMedia;
                    $unitiesSem2++;
                }
            } elseif ($gradeUnity->type == GradeUnity::TYPE_FINAL_RECOVERY) {
                $gradeResult = $this->calculateFinalRecovery($gradeResult, $studentEnrollment, $discipline, $gradeUnity);
            }

            if ($gradeUnity->parcial_recovery_fk !== null) {
                $exist = false;
                foreach ($gradesRecoveries as $key => $gradeRecoveryAndUnities) {
                    if ($gradeRecoveryAndUnities["partialRecovery"]->id == $gradeUnity->parcialRecoveryFk->id) {
                        $exist = true;
                        array_push($gradesRecoveries[$key]["unities"], ($index + 1));
                    }
                }
                if (!$exist) {
                    array_push($gradesRecoveries, ["partialRecovery" => $gradeUnity->parcialRecoveryFk, "unities" => [$index + 1]]);
                }
            }

        }

        if($unitiesSem1 != 0) {
            $gradeResult["sem_avarage_1"] = is_nan(($semAvarage1 / $unitiesSem1) ?? NAN) ? null : round(($semAvarage1 / $unitiesSem1), 1);
        }

        if($unitiesSem2 != 0) {
            $gradeResult["sem_avarage_2"] = is_nan(($semAvarage2 / $unitiesSem2) ?? NAN) ? null : round(($semAvarage2 / $unitiesSem2), 1);
        }
        $gradeResult = $this->calculatePartialRecovery($gradeResult, $studentEnrollment, $discipline, $gradesRecoveries);

        $gradeResult->setAttribute("final_media", null);
        $gradeResult->setAttribute("situation", null);
        if ($gradeResult->save()) {
            TLog::info("GradeResult para nota numérica salvo com sucesso.", array(
                "GradeResult" => $gradeResult->id
            ));
        }

        return $gradeResult;
    }
    private function calculatePartialRecovery($gradeResult, $studentEnrollment, $discipline, $gradesRecoveries)
    {
        foreach ($gradesRecoveries as $gradeRecoveryAndUnities) {
            $partialRecoveryMedia = $this->calculatePartialRecoveryMedia($studentEnrollment, $discipline, $gradeRecoveryAndUnities, $gradeResult);
            if ($partialRecoveryMedia != null) {
                $partialRecoveryMedia = is_nan($partialRecoveryMedia ?? NAN) ? "" : round($partialRecoveryMedia, 1);
            }
            if ($gradeRecoveryAndUnities["partialRecovery"]->semester != null) {
                $semesterRec = $gradeRecoveryAndUnities["partialRecovery"]->semester;
                if ($partialRecoveryMedia != null) {
                    $gradeResult["sem_rec_partial_" . $semesterRec] = $partialRecoveryMedia < $gradeResult["sem_avarage_" . $semesterRec] ? $gradeResult["sem_avarage_" . $semesterRec] : $partialRecoveryMedia;
                } else {
                    $gradeResult["sem_rec_partial_" . $semesterRec] = $partialRecoveryMedia;
                }
            } else {
                $gradeResult["rec_partial_" . $gradeRecoveryAndUnities["partialRecovery"]->order_partial_recovery] = $partialRecoveryMedia;
            }
        }

        return $gradeResult;
    }

    private function calculateFinalRecovery($gradeResult, $studentEnrollment, $discipline, $unity)
    {
        $unityMedia = $this->calculateFinalRecoveryMedia($studentEnrollment, $discipline, $unity);
        $gradeResult->setAttribute("rec_final", is_nan($unityMedia ?? NAN) ? "" : $unityMedia);

        return $gradeResult;
    }

    private function calculateCommonUnity($gradeResult, $studentEnrollment, $discipline, $unity, $index)
    {
        $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $unity);
        $gradeResult["grade_" . ($index + 1)] = is_nan($unityMedia ?? NAN) ? "" : round($unityMedia, 1);
        return $gradeResult;
    }

    private function calculateUnityWithRecovery($gradeResult, $studentEnrollment, $discipline, $unity, $index)
    {
        $unityMedia = $this->calculateUnityMedia($studentEnrollment, $discipline, $unity);
        $recoveryGrade = $this->getRecoveryGradeFromUnity($studentEnrollment->id, $discipline, $unity->id);
        $gradeWithRecovery = [(float) $unityMedia, (float) $recoveryGrade->grade];
        $finalUnityMedia = max($gradeWithRecovery);
        $gradeResult["grade_" . ($index + 1)] = is_nan($finalUnityMedia ?? NAN) ? "" : $finalUnityMedia;
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

    private function getGradeUnitiesByClassroomStage()
    {

        if(isset($this->stage) && $this->stage !== "") {
            $criteria = new CDbCriteria();
            $criteria->alias = 'gu';
            $criteria->join = 'join grade_rules gr on gr.id = gu.grade_rules_fk';
            $criteria->join .= ' join grade_rules_vs_edcenso_stage_vs_modality grvesvm on gr.id = grvesvm.grade_rules_fk';
            $criteria->join .= ' join classroom_vs_grade_rules cvgr on cvgr.grade_rules_fk = gr.id';
            $criteria->condition = 'grvesvm.edcenso_stage_vs_modality_fk = :stage and cvgr.classroom_fk = :classroom';
            $criteria->params = array(':classroom' => $this->classroomId, ":stage"=>$this->stage);

            return GradeUnity::model()->count($criteria);
        } else {
            $criteria = new CDbCriteria();
            $criteria->alias = 'gu';
            $criteria->join = 'INNER JOIN grade_rules gr ON gr.id = gu.grade_rules_fk';
            $criteria->join .= ' INNER JOIN classroom_vs_grade_rules cgr ON cgr.grade_rules_fk = gu.grade_rules_fk';
            $criteria->join .= ' INNER JOIN classroom c ON c.id = cgr.classroom_fk';
            $criteria->join .= ' INNER JOIN grade_rules_vs_edcenso_stage_vs_modality grvesvm ON grvesvm.edcenso_stage_vs_modality_fk = c.edcenso_stage_vs_modality_fk';
            $criteria->condition = 'cgr.classroom_fk = :classroomId';
            $criteria->params = array(':classroomId' => $this->classroomId);

            return GradeUnity::model()->count($criteria);

        }
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
    private function calculatePartialRecoveryMedia($enrollment, $disciplineId, $gradeRecoveryAndUnities, $gradeResult)
    {

        $grades = [];
        $partialRecovery = $gradeRecoveryAndUnities["partialRecovery"];
        $calculationName = $partialRecovery->gradeCalculationFk->name;
        $gradePartialRecovery = Grade::model()->findByAttributes([
            "enrollment_fk" => $enrollment->id,
            "discipline_fk" => $disciplineId,
            "grade_partial_recovery_fk" => $partialRecovery->id
        ]);
        if ($calculationName == 'Média Semestral') {
            $semester = GradeUnity::model()->findByAttributes(["parcial_recovery_fk" => $partialRecovery->id])->semester;
            array_push($grades, $gradeResult['sem_avarage_' . $semester]);

        } else {
            foreach ($gradeRecoveryAndUnities["unities"] as $unity) {
                array_push($grades, $gradeResult['grade_' . $unity]);
            }
        }

        $result = null;
        if ($gradePartialRecovery !== null && $gradePartialRecovery->grade !== null) {
            // Adiciona o valor de gradePartialRecovery->grade na primeira posição do array grades
            array_unshift($grades, $gradePartialRecovery->grade);
            $isRecovery = true;

            $calculation = $calculationName == 'Média Semestral' ? 'Média' : $calculationName;

            $result = $this->applyStrategyComputeGradesByFormula($calculation, $partialRecovery, $grades, $isRecovery);
        }

        return $result;
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
        $isRecovery = false;
        return $this->applyStrategyComputeGradesByFormula($unity->gradeCalculationFk->name, $unity, array_column($grades, "grade"), $isRecovery);
    }

    private function calculateFinalRecoveryMedia($enrollment, $disciplineId, $unity)
    {

        $grades = $this->getRecoveryGradeFromUnity(
            $enrollment->id,
            $disciplineId,
            $unity->id
        );
        $isRecovery = false;
        return $this->applyStrategyComputeGradesByFormula($unity->gradeCalculationFk->name, $unity, [$grades->grade], $isRecovery);
    }

    /**
     * @param GradeUnity $unity
     * @param float[] $grades
     */
    private function applyStrategyComputeGradesByFormula($calculation, $unityOrRecovery, $grades, $isRecovery)
    {
        $result = 0;

        if(empty($grades)){ return 0; }

        switch ($calculation) {
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
                if ($isRecovery == false) {
                    $weights = $unityOrRecovery->gradeUnityModalities;
                } else {
                    $weights = GradePartialRecoveryWeights::model()->findAllByAttributes(["partial_recovery_fk" => $unityOrRecovery->id]);
                }

                foreach ($grades as $key => $grade) {
                    $acc[0] += $grade * $weights[$key]->weight;
                    $acc[1] += $weights[$key]->weight;
                }
                if($acc[1] == 0){
                    return 0;
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
