<?php
/**
 * @property int $classroomId
 * @property int $disciplineId
 */
class GetStudentGradesByDisciplineUsecase
{

    public function __construct(int $classroomId, int $disciplineId)
    {
        $this->classroomId = $classroomId;
        $this->disciplineId = $disciplineId;
    }
    public function exec()
    {
        /** @var Classroom $classroom */
        $classroom = Classroom::model()->with("activeStudentEnrollments.studentFk")->findByPk($this->classroomId);
        $rules = GradeRules::model()->findByAttributes([
            "edcenso_stage_vs_modality_fk" => $classroom->edcenso_stage_vs_modality_fk
        ]);
        $studentEnrollments = $classroom->activeStudentEnrollments;
        $unitiesByDiscipline = $this->getGradeUnitiesByDiscipline($classroom->edcenso_stage_vs_modality_fk);

        if ($studentEnrollments == null) {
            throw new NoActiveStudentsException();
        }

        $classroomGrades = [];
        foreach ($studentEnrollments as $enrollment) {
            $classroomGrades[] = $this->getStudentGradeByDicipline(
                $enrollment,
                $this->disciplineId,
                $unitiesByDiscipline
            );
        }

        $unityColumns = [];

        foreach ($unitiesByDiscipline as $unity) {
            $unityColumns[] = [
                "name" => $unity->name,
                "colspan" => $unity->countGradeUnityModalities + ($unity->type === GradeUnity::TYPE_UNITY_WITH_RECOVERY ? 1 : 0),
                "modalities" => array_column($unity->gradeUnityModalities, 'name'),
                "calculationName" => $unity->gradeCalculationFk->name
            ];
        }

        $result = new StructClassromGradeResult();
        $result->setStudents($classroomGrades)
            ->setIsUnityConcept(false)
            ->setUnityColumns($unityColumns);

        if ($rules->rule_type === "C") {
            $concepts = GradeConcept::model()->findAll();
            $result->setIsUnityConcept(true)
                ->setConcepts(CHtml::listData($concepts, 'id', 'name'));
        }

        return $result->toArray();
    }

    /**
     * @return GradeUnity[]
     */
    private function getGradeUnitiesByDiscipline($stage)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->select = "distinct gu.id, gu.*";
        $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
        $criteria->condition = "edcenso_stage_vs_modality_fk = :stage";
        $criteria->params = array(":stage" => $stage);
        $criteria->order = "gu.type desc, gu.id";
        return GradeUnity::model()->findAll($criteria);
    }

    /**
     * Get Student grades by discipline
     *
     * @var GradeUnity $unitiesByDiscipline
     *
     * @return StudentGradesResult
     */
    private function getStudentGradeByDicipline($studentEnrollment, $discipline, $unitiesByDiscipline)
    {
        $studentGradeResult = new StudentGradesResult($studentEnrollment->studentFk->name, $studentEnrollment->id);

        $gradeResult = GradeResults::model()->find(
            "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
            [
                "enrollment_fk" => $studentEnrollment->id,
                "discipline_fk" => $discipline
            ]
        );

        if ($gradeResult == null) {
            $gradeResult = new GradeResults();
            $gradeResult->enrollment_fk = $studentEnrollment->id;
            $gradeResult->discipline_fk = $discipline;
            $gradeResult->save();
        }

        $studentGradeResult->setFinalMedia($gradeResult->final_media);
        $studentGradeResult->setSituation($gradeResult->situation);

        foreach ($unitiesByDiscipline as $key => $unity) {
            /** @var GradeUnity $unit */
            $unityGrades = $this->getStudentGradesFromUnity($studentEnrollment->id, $discipline, $unity->id);
            $unityResult = new GradeUnityResult($unity->name, $unity->gradeCalculationFk->name);

            if ($unity->type == GradeUnity::TYPE_UNITY || $unity->type == GradeUnity::TYPE_UNITY_WITH_RECOVERY) {
                $unityResult->setUnityMedia($gradeResult["grade_" . ($key + 1)]);
            } elseif ($unity->type == GradeUnity::TYPE_FINAL_RECOVERY) {
                $unityResult->setUnityMedia($gradeResult["rec_final"]);
            } elseif ($unity->type == GradeUnity::TYPE_UNITY_BY_CONCEPT) {
                $unityResult->setUnityMedia($gradeResult["grade_concept_" . ($key + 1)]);
            }


            foreach ($unity->gradeUnityModalities as $modality) {
                $grade;
                $gradeIndex = array_search($modality->id, array_column($unityGrades, "grade_unity_modality_fk"));
                if ($gradeIndex !== false) {
                    $grade = $unityGrades[$gradeIndex];
                } else {
                    $grade = new Grade();
                    $grade->enrollment_fk = $studentEnrollment->id;
                    $grade->discipline_fk = $discipline;
                    $grade->grade_unity_modality_fk = $modality->id;
                    $grade->grade = 0;
                    $grade->validate();
                    $grade->save();
                }

                $gradeByModality = new GradeByModalityResult();
                $gradeByModality->setValue($grade->grade);
                $gradeByModality->setModalityId($modality->id);
                $gradeByModality->setGradeId($grade->id);
                $gradeByModality->setConcept($grade->grade_concept_fk);
                $unityResult->addGrade($gradeByModality);
            }


            $studentGradeResult->addUnity($unityResult);
        }

        return $studentGradeResult;
    }

    /**
     * @return Grade[]
     */
    private function getStudentGradesFromUnity($enrollmentId, $discipline, $unityId): array
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

/**
 * @property StudentGradesResult[] $students
 * @property array $concepts
 * @property array $unityColumns
 * @property bool $isUnityConcept
 */
class StructClassromGradeResult
{
    private $students;
    private $concepts;
    private $isUnityConcept;

    private $unityColumns;

    /**
     * Get the value of students
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Set the value of students
     *
     * @return  self
     */
    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }

    /**
     * Get the value of concepts
     */
    public function getConcepts()
    {
        return $this->concepts;
    }

    /**
     * Set the value of unityColumns
     *
     * @return  self
     */
    public function setUnityColumns($unityColumns)
    {
        $this->unityColumns = $unityColumns;

        return $this;
    }

    /**
     * Get the value of unityColumns
     */
    public function getUnityColumns()
    {
        return $this->unityColumns;
    }

    /**
     * Set the value of concepts
     *
     * @return  self
     */
    public function setConcepts($concepts)
    {
        $this->concepts = $concepts;

        return $this;
    }

    /**
     * Get the value of isUnityConcept
     */
    public function getIsUnityConcept()
    {
        return $this->isUnityConcept;
    }

    /**
     * Set the value of isUnityConcept
     *
     * @return  self
     */
    public function setIsUnityConcept($isUnityConcept)
    {
        $this->isUnityConcept = $isUnityConcept;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'students' => array_map(function (StudentGradesResult $studentGradesResult) {
                return $studentGradesResult->toArray();
            }, $this->students),
            'unityColumns' => $this->unityColumns,
            'concepts' => $this->concepts,
            'isUnityConcept' => $this->isUnityConcept
        ];
    }

}

/**
 * @property string $studentName
 * @property int $enrollmentId
 * @property string $finalMedia
 * @property string $situation
 * @property GradeUnityResult[] $unities
 */
class StudentGradesResult
{
    private $studentName;
    private $enrollmentId;
    private $finalMedia;
    private $situation;
    private $unities;

    public function __construct($studentName, $enrollmentId)
    {
        $this->studentName = $studentName;
        $this->enrollmentId = $enrollmentId;
    }

    public function getStudentName()
    {
        return $this->studentName;
    }

    public function setFinalMedia($finalMedia)
    {
        $this->finalMedia = $finalMedia;
        return $this;
    }

    public function setSituation($situation)
    {
        $this->situation = $situation;

        return $this;
    }

    public function getUnities()
    {
        return $this->unities;
    }
    public function addUnity(GradeUnityResult $unity)
    {
        $this->unities[] = $unity;
    }

    public function toArray(): array
    {
        return [
            'studentName' => $this->studentName,
            'enrollmentId' => $this->enrollmentId,
            'finalMedia' => $this->finalMedia,
            'situation' => $this->situation,
            'unities' => array_map(function (GradeUnityResult $unity) {
                return $unity->toArray();
            }, $this->unities),
        ];
    }
}

/**
 * @property string $unityName
 * @property GradeByModalityResult[] $grades
 * @property string|null $unityMedia
 */
class GradeUnityResult
{
    private $unityName;
    private $grades;
    private $unityMedia;
    private $calculationName;

    public function __construct($unityName = null, $calculationName = null)
    {
        $this->unityName = $unityName;
        $this->calculationName = $calculationName;
    }

    public function setUnityMedia($unityMedia)
    {
        $this->unityMedia = $unityMedia;
    }

    public function addGrade(GradeByModalityResult $grade)
    {
        $this->grades[] = $grade;
    }

    public function toArray(): array
    {
        return [
            'unityName' => $this->unityName,
            'calculationName' => $this->calculationName,
            'grades' => array_map(function (GradeByModalityResult $grade) {
                return $grade->toArray();
            }, $this->grades),
            'unityMedia' => $this->unityMedia
        ];
    }
}

/**
 * @property int $id
 * @property int $modalityId
 * @property int $concept
 * @property string $value
 */
class GradeByModalityResult
{
    private $id;
    private $modalityId;
    private $concept;
    private $value;

    public function getModalityId(): string
    {
        return $this->modalityId;
    }

    public function setGradeId(int $id)
    {
        $this->id = $id;
    }

    public function getConcept(): string
    {
        return $this->concept;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setModalityId(string $modalityId): self
    {
        $this->modalityId = $modalityId;
        return $this;
    }

    public function setConcept($concept): self
    {
        $this->concept = $concept;
        return $this;
    }

    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'modalityId' => $this->modalityId,
            'concept' => $this->concept,
            'value' => $this->value
        ];
    }
}
