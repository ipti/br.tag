<?php

/**
 * @property int $classroomId
 * @property int $discipline
 * @property int $stage
 */
class CalculateConceptGradeUsecase
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
        $TotalEnrollments = $classroom->activeStudentEnrollments;
        $studentEnrollments = [];
        if(TagUtils::isMultiStage($classroom->edcenso_stage_vs_modality_fk)) {

            foreach ($TotalEnrollments as $enrollment) {
                if($enrollment->edcenso_stage_vs_modality_fk == $this->stage){
                    array_push($studentEnrollments, $enrollment);
                }
            }
        } else {
            $studentEnrollments = $classroom->activeStudentEnrollments;
            $this->stage = $classroom->edcenso_stage_vs_modality_fk;

        }
        // $unitiesByDiscipline = $this->getGradeUnitiesByClassroomStage($this->classroomId);

        foreach ($studentEnrollments as $studentEnrollment) {
            $this->calculateConceptGrades($studentEnrollment, $this->discipline);
        }
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

    private function calculateConceptGrades($studentEnrollment, $disciplineId)
    {

        $gradeResult = $this->getGradesResultForStudent($studentEnrollment->id, $disciplineId);
        //notas por conceito
        $hasAllGrades = true;
        $conceptGradeValues = 0;
        $grades = $this->getStudentGrades(
            $studentEnrollment->id,
            $disciplineId,
        );
        foreach ($grades as  $gradeKey => $grade) {

            $gradeResult["grade_concept_" . ($gradeKey + 1)] = $grade->gradeConceptFk->acronym;
            $conceptGradeValues += $grade->gradeConceptFk->value;
        }

        $gradeUnities = new GetGradeUnitiesByDisciplineUsecase($this->classroomId, $this->stage);
        $numUnities = $gradeUnities->execCount();

        if (!$this->hasAllGrades($numUnities, $gradeResult)) {
            $hasAllGrades = false;
        }

        $gradeResult->final_media = $conceptGradeValues/$numUnities;
        $gradeResult->situation = StudentEnrollment::STATUS_ACTIVE;

        if ($hasAllGrades) {
            $gradeResult->situation = StudentEnrollment::STATUS_APPROVED;
        }

        if ($gradeResult->save()) {
            TLog::info("GradesResult para nota por conceito salvo com sucesso.", array(
                "GradesResult" => $gradeResult->id
            ));
        }
    }




    /**
     * @param int $enrollmentId
     * @param int $discipline
     * @param int $unityId
     *
     * @return Grade[]
     */
    private function getStudentGrades($enrollmentId, $discipline)
    {

        $gradesIds = array_column(Yii::app()->db->createCommand(
            "SELECT
                g.id
                FROM grade g
                join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                join grade_unity gu on gu.id= gum.grade_unity_fk
                WHERE g.enrollment_fk = :enrollment_id and g.discipline_fk = :discipline_id and gum.type = '" . GradeUnityModality::TYPE_COMMON . "' and gu.type = 'UC'"
        )->bindParam(":enrollment_id", $enrollmentId)
            ->bindParam(":discipline_id", $discipline)->queryAll(), "id");

        if ($gradesIds == null) {
            return [];
        }

        return  Grade::model()->findAll(
            array(
                'condition' => 'id IN (' . implode(',', $gradesIds) . ')',
            )
        );
    }

    private function hasAllGrades($numUnities, $gradeResult)
    {
        for ($i = 1; $i <= $numUnities; $i++) {
            if (!isset($gradeResult["grade_concept_" . $i]) || $gradeResult["grade_concept_" . $i] === "") {
                return false;
            }
        }
        return true;
    }

}
