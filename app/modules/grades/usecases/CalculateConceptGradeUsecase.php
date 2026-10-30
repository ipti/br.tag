<?php

/**
 * @property int $classroomId
 * @property int $discipline
 * @property int $stage
 */
class CalculateConceptGradeUsecase
{
    // grade_results tem as colunas grade_concept_1 a grade_concept_8
    private const MAX_CONCEPT_COLUMNS = 8;

    private $classroomId;
    private $discipline;
    private $stage;
    private $unityPositions = null;
    private $gradeRulesIds = [];

    public function __construct($classroom, $discipline, $stage)
    {
        $this->classroomId = $classroom;
        $this->discipline = $discipline;
        $this->stage = $stage;
    }

    public function exec()
    {
        $classroom = Classroom::model()->with('activeStudentEnrollments.studentFk')->findByPk($this->classroomId);
        $totalEnrollments = $classroom->activeStudentEnrollments;
        $studentEnrollments = [];
        if (TagUtils::isMultiStage($classroom->edcenso_stage_vs_modality_fk)) {
            foreach ($totalEnrollments as $enrollment) {
                if ($enrollment->edcenso_stage_vs_modality_fk == $this->stage) {
                    array_push($studentEnrollments, $enrollment);
                }
            }
        } else {
            $studentEnrollments = $classroom->activeStudentEnrollments;
            $this->stage = $classroom->edcenso_stage_vs_modality_fk;
        }
        foreach ($studentEnrollments as $studentEnrollment) {
            $this->calculateConceptGrades($studentEnrollment, $this->discipline);
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
            'enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk',
            [
                'enrollment_fk' => $studentEnrollmentId,
                'discipline_fk' => $disciplineId
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

        // Só contam as notas das unidades da estrutura ligada à turma, cada
        // uma na posição da sua unidade. Antes as notas eram gravadas pela
        // ordem em que vinham, de qualquer estrutura: se a turma trocou de
        // estrutura no ano, as notas antigas empurravam as atuais para a
        // coluna seguinte, e uma unidade sem nota deslocava as demais.
        $unityPositions = $this->getUnityPositions();
        $numUnities = count($unityPositions);

        for ($position = 1; $position <= self::MAX_CONCEPT_COLUMNS; $position++) {
            $gradeResult['grade_concept_' . $position] = null;
        }

        $grades = $this->getStudentGrades(
            $studentEnrollment->id,
            $disciplineId,
            array_keys($unityPositions)
        );
        foreach ($grades as $grade) {
            if ($grade->gradeConceptFk === null) {
                continue;
            }
            $position = $unityPositions[$grade->gradeUnityModalityFk->grade_unity_fk];
            $gradeResult['grade_concept_' . $position] = $grade->gradeConceptFk->acronym;
            $conceptGradeValues += $grade->gradeConceptFk->value;
        }

        if (!$this->hasAllGrades($numUnities, $gradeResult)) {
            $hasAllGrades = false;
        }

        $criteria = new CDbCriteria();
        $criteria->alias = 'g';
        $criteria->join = 'INNER JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk ';
        $criteria->join .= 'INNER JOIN grade_unity gu ON gu.id = gum.grade_unity_fk ';
        $criteria->condition = 'g.enrollment_fk = :enrollmentId
        and g.discipline_fk = :disciplineId
        and gum.type = :modalityType and gu.type = :unityType';
        $criteria->params = [
            ':enrollmentId' => $studentEnrollment->id,
            ':disciplineId' => $disciplineId,
            ':modalityType' => GradeUnityModality::TYPE_FINAL_CONCEPT,
            ':unityType' => GradeUnity::TYPE_FINAL_CONCEPT
        ];
        $criteria->addInCondition('gu.grade_rules_fk', $this->gradeRulesIds);

        $finalConceptGrade = Grade::model()->find($criteria);
        $fianlConcept = $finalConceptGrade !== null ? $finalConceptGrade->grade_concept_fk : null;
        $gradeResult->final_concept = $fianlConcept ? $fianlConcept : null;
        $gradeResult->final_media = $conceptGradeValues / $numUnities;
        $gradeResult->situation = StudentEnrollment::STATUS_ACTIVE;

        if ($hasAllGrades) {
            $gradeResult->situation = StudentEnrollment::STATUS_APPROVED;
        }

        if ($gradeResult->save()) {
            TLog::info('GradesResult para nota por conceito salvo com sucesso.', [
                'GradesResult' => $gradeResult->id
            ]);
        }
    }

    /**
     * Posição (1, 2, ...) de cada unidade da estrutura de avaliação da turma,
     * na mesma ordem em que os relatórios leem as colunas grade_concept_N.
     *
     * @return array<int, int> id da unidade => posição
     */
    private function getUnityPositions()
    {
        if ($this->unityPositions === null) {
            $gradeUnities = (new GetGradeUnitiesByDisciplineUsecase($this->classroomId, $this->stage))->exec();
            usort($gradeUnities, static fn ($a, $b) => $a->id <=> $b->id);

            $this->unityPositions = [];
            $this->gradeRulesIds = [];
            foreach ($gradeUnities as $index => $gradeUnity) {
                $this->unityPositions[(int) $gradeUnity->id] = $index + 1;
                $this->gradeRulesIds[(int) $gradeUnity->grade_rules_fk] = (int) $gradeUnity->grade_rules_fk;
            }
            $this->gradeRulesIds = array_values($this->gradeRulesIds);
        }

        return $this->unityPositions;
    }

    /**
     * @param int $enrollmentId
     * @param int $discipline
     * @param int[] $unityIds
     *
     * @return Grade[]
     */
    private function getStudentGrades($enrollmentId, $discipline, array $unityIds)
    {
        if (empty($unityIds)) {
            return [];
        }

        $gradesIds = array_column(Yii::app()->db->createCommand(
            "SELECT
                g.id
                FROM grade g
                join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                join grade_unity gu on gu.id= gum.grade_unity_fk
                WHERE g.enrollment_fk = :enrollment_id and g.discipline_fk = :discipline_id and gum.type = '" . GradeUnityModality::TYPE_COMMON . "' and gu.type = 'UC'
                and gu.id IN (" . implode(',', array_map('intval', $unityIds)) . ')'
        )->bindParam(':enrollment_id', $enrollmentId)
            ->bindParam(':discipline_id', $discipline)->queryAll(), 'id');

        if ($gradesIds == null) {
            return [];
        }

        return  Grade::model()->findAll(
            [
                'condition' => 'id IN (' . implode(',', $gradesIds) . ')',
                'order' => 'grade_unity_modality_fk'
            ]
        );
    }

    private function hasAllGrades($numUnities, $gradeResult)
    {
        for ($i = 1; $i <= $numUnities; $i++) {
            if (!isset($gradeResult['grade_concept_' . $i]) || $gradeResult['grade_concept_' . $i] === '') {
                return false;
            }
        }
        return true;
    }
}
