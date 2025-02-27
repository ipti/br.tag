<?php
/**
 * @property int $classroomId
 * @property int $disciplineId
 * @property int $unityId
 * @property int $stageId
 * @property int isClassroomStage
 */
class GetStudentGradesByDisciplineUsecase
{
    private $classroomId;
    private $disciplineId;
    private $unityId;
    private $stageId;
    private $isClassroomStage;

    public function __construct(int $classroomId, int $disciplineId, int $unityId, int $stageId, int $isClassroomStage)
    {
        $this->classroomId = $classroomId;
        $this->disciplineId = $disciplineId;
        $this->unityId = $unityId;
        $this->stageId = $stageId;
        $this->isClassroomStage = $isClassroomStage;
    }
    public function exec()
    {
        /** @var Classroom $classroom */
        $classroom = Classroom::model()->with("activeStudentEnrollments.studentFk")->findByPk($this->classroomId);

        if ($classroom == null) {
            throw new NoActiveStudentsException();
        }
        $rules = $classroom->getGradeRules($this->stageId);


        $totalEnrollments = $classroom->activeStudentEnrollments;
        $studentEnrollments = [];
        if(TagUtils::isMultiStage($classroom->edcenso_stage_vs_modality_fk) && $this->isClassroomStage == 0){
            foreach ($totalEnrollments as $enrollment) {
                if($enrollment->edcenso_stage_vs_modality_fk == $this->stageId){
                    array_push($studentEnrollments, $enrollment);
                }
            }
        } else {
            $studentEnrollments= $classroom->activeStudentEnrollments;
        }
        $showSemAvarageColumn = $this->checkSemesterUnities( $classroom->id, $this->stageId) && $rules->gradeCalculationFk->name == 'Média Semestral';

        $unitiesByDisciplineResult = $this->getGradeUnitiesByDiscipline( $rules->id);
        $unitiesByDiscipline = array_filter($unitiesByDisciplineResult, function ($item){
            return $item["id"] == $this->unityId;
        });
        $unitiesByDiscipline = array_values($unitiesByDiscipline);

        $unityOrder = $this->searchUnityById($unitiesByDisciplineResult);

        if ($studentEnrollments == []) {
            throw new NoActiveStudentsException();
        }



        $unityColumns = [];

        foreach ($unitiesByDiscipline as $unity) {
            $unityColumns[] = [
                "name" => $unity->name,
                "colspan" => $unity->countGradeUnityModalities + ($unity->type === GradeUnity::TYPE_UNITY_WITH_RECOVERY ? 1 : 0),
                "modalities" => array_column($unity->gradeUnityModalities, 'name'),
                "calculationName" => $unity->gradeCalculationFk->name,
                "recoveryPartialFk" => $unity->parcial_recovery_fk == null ? "" : $unity->parcial_recovery_fk,
                "type" => $unity->type
            ];
        }
        $semester = null;
        $type = null;
        if(count($unitiesByDiscipline) == 1) {
            $semester = $unitiesByDiscipline[0]->semester;
            $type = $unitiesByDiscipline[0]->type;

        }

        $classroomGrades = [];
        foreach ($studentEnrollments as $enrollment) {
            $classroomGrades[] = $this->getStudentGradeByDicipline(
                $enrollment,
                $this->disciplineId,
                $unitiesByDiscipline,
                $unityOrder,
                $type,
                $semester,
                $showSemAvarageColumn,
                $rules
            );
        }
        $partialRecoveryColumns = null;
        $partialRecovery = $this->getpartialRecoveriesByUnity();

        if( $partialRecovery != null) {
            $partialRecoveryColumns = [
                    "id" => $partialRecovery->id,
                    "name" => $partialRecovery->name,
                    "colspan" => 0,
                    "modalities" => $partialRecovery->name,
                    "calculationName" => $partialRecovery->gradeCalculationFk->name,
            ];
        }
        $result = new StructClassromGradeResult();
        $result->setStudents($classroomGrades)
            ->setIsUnityConcept(false)
            ->setUnityColumns($unityColumns)
            ->setPartialRecoveryColumns($partialRecoveryColumns)
            ->setSemester($semester)
            ->setType($type)
            ->setShowSemAvarageColumn($showSemAvarageColumn);

        if ($rules->rule_type === "C") {
            $concepts = GradeConcept::model()->findAll();
            $result->setIsUnityConcept(true)
                ->setConcepts(CHtml::listData($concepts, 'id', 'name'));
        }

        return $result->toArray();
    }
    public function checkSemesterUnities($classroomId, $stage) {

        $criteria = new CDbCriteria();
        $criteria->alias = 'gu';
        $criteria->join = 'join grade_rules gr on gr.id = gu.grade_rules_fk';
        $criteria->join .= ' join grade_rules_vs_edcenso_stage_vs_modality grvesvm on gr.id = grvesvm.grade_rules_fk';
        $criteria->join .= ' join classroom_vs_grade_rules cvgr on cvgr.grade_rules_fk = gr.id';
        $criteria->condition = 'grvesvm.edcenso_stage_vs_modality_fk = :stage and cvgr.classroom_fk = :classroom and type != :type and semester IS NULL';
        $criteria->params = array(':classroom' => $classroomId, ":stage"=>$stage, ':type' => 'RF');
        $unities = GradeUnity::model()->findAll($criteria);
        $unitiesTypeUC = count(GradeUnity::model()->findAllByAttributes(['edcenso_stage_vs_modality_fk' => $stage, "type"=>"UC"]));
        $unitiesCount = count($unities);

        return $unitiesCount == 0 && $unitiesTypeUC == 0;
    }
    private function searchUnityById($unities) {
        foreach ($unities as $key => $unity) {
            if ($unity->id == $this->unityId) {
                return $key ;
            }
        }
        return false;
    }
    /**
     * @return GradeUnity[]
     */
    private function getGradeUnitiesByDiscipline($gradeRulesId)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->select = "distinct gu.id, gu.*";
        $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
        $criteria->condition = "grade_rules_fk = :grade_rules_fk";
        $criteria->params = array(":grade_rules_fk" => $gradeRulesId);
        $criteria->order = "gu.type desc, gu.id";
        return GradeUnity::model()->findAll($criteria);
    }

    /**
     * @return GradePartialRecovery
     */
    private function getpartialRecoveriesByUnity() {
        $criteria = new CDbCriteria();
        $criteria->alias = "gpr";
        $criteria->select = "distinct gpr.id, gpr.*";
        $criteria->join = "join grade_unity gu on gu.parcial_recovery_fk = gpr.id";
        $criteria->condition = "gu.id = :unitId";
        $criteria->params = array(":unitId"=>$this->unityId);
        return GradePartialRecovery::model()->find($criteria);
    }
    public function getSemRecPartial ($gradeResult, $semester, $type) {
        if($type == "RF") {
            if($gradeResult->sem_avarage_1 === null && $gradeResult->sem_avarage_2 === null) {
                return "";
            } elseif($gradeResult->sem_avarage_1 !== null && $gradeResult->sem_avarage_2 === null){
                return $gradeResult->sem_rec_partial_1  < $gradeResult->sem_avarage_1 ?  $gradeResult->sem_avarage_1 : $gradeResult->sem_rec_partial_1;
            } elseif($gradeResult->sem_avarage_1 === null && $gradeResult->sem_avarage_2 !== null){
                return $gradeResult->sem_rec_partial_2  < $gradeResult->sem_avarage_2 ?  $gradeResult->sem_avarage_2 : $gradeResult->sem_rec_partial_2;
            }

            $semRecPartial1 = is_numeric($gradeResult->sem_rec_partial_1) ? $gradeResult->sem_rec_partial_1 : 0;
            $semRecPartial2 = is_numeric($gradeResult->sem_rec_partial_2) ? $gradeResult->sem_rec_partial_2 : 0;

            $gradesSemAvarage1 =  max($gradeResult->sem_avarage_1, $semRecPartial1);
            $gradesSemAvarage2 =  max($gradeResult->sem_avarage_2, $semRecPartial2);

            return round((($gradesSemAvarage1 + $gradesSemAvarage2)/2), 1);

        } else {
            if($semester == 1) {
                return $gradeResult->sem_avarage_1 === null ? "" : $gradeResult->sem_avarage_1;
            } elseif($semester == 2) {
                return $gradeResult->sem_avarage_2 === null ? "" : $gradeResult->sem_avarage_2;
            }
        }
    }

    /**
     * Get Student grades by discipline
     *
     * @var GradeUnity $unitiesByDiscipline
     *
     * @return StudentGradesResult
     */
    private function getStudentGradeByDicipline($studentEnrollment, $discipline, $unitiesByDiscipline, $unityOrder,$type, $semester, $showSemAvarageColumn, $rules)
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

        $semRecPartial = null;
        if($showSemAvarageColumn) {
            $semRecPartial = $this->getSemRecPartial($gradeResult, $semester, $type);
        } else {
            $semRecPartial = "";
        }

        $finalMedia  = $gradeResult->final_media === null || $gradeResult->final_media >=  $gradeResult->rec_final ? $gradeResult->final_media : $gradeResult->rec_final;
        $studentGradeResult->setSemAvarage($semRecPartial);
        $studentGradeResult->setFinalMedia($finalMedia);
        $studentGradeResult->setSituation($gradeResult->situation);


        foreach ($unitiesByDiscipline as $key => $unity) {
            /** @var GradeUnity $unit */
            $unityGrades = $this->getStudentGradesFromUnity($studentEnrollment->id, $discipline, $unity->id);
            $unityResult = new GradeUnityResult($unity->name, $unity->gradeCalculationFk->name);

            if ($unity->type == GradeUnity::TYPE_UNITY || $unity->type == GradeUnity::TYPE_UNITY_WITH_RECOVERY) {
                $unityResult->setUnityMedia($gradeResult["grade_" . ($unityOrder + 1)]);
            } elseif ($unity->type == GradeUnity::TYPE_FINAL_RECOVERY) {
                $unityResult->setUnityMedia($gradeResult["rec_final"]);
            } elseif ($unity->type == GradeUnity::TYPE_UNITY_BY_CONCEPT) {
                $unityResult->setUnityMedia($gradeResult["grade_concept_" . ($unityOrder + 1)]);
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

            if($unity->parcial_recovery_fk !== null){
                $gradePartialRecovery = Grade::model()->find(
                    "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk and grade_partial_recovery_fk = :grade_partial_recovery_fk",
                    [
                        "enrollment_fk" => $studentEnrollment->id,
                        "discipline_fk" => $discipline,
                        "grade_partial_recovery_fk" => $unity->parcial_recovery_fk
                    ]
                );

                if($gradePartialRecovery == null) {
                    $gradePartialRecovery = new Grade();
                    $gradePartialRecovery->enrollment_fk = $studentEnrollment->id;
                    $gradePartialRecovery->discipline_fk = $discipline;
                    $gradePartialRecovery->grade = null;
                    $gradePartialRecovery->grade_partial_recovery_fk = $unity->parcial_recovery_fk;
                    $gradePartialRecovery->save();
                }


                $partialRecovery = $unity->parcialRecoveryFk;
                $partialRecoveryResult = new GradePartialRecoveryResult($partialRecovery->name,
                $partialRecovery->gradeCalculationFk->name);
                $partialRecoveryResult->addGrade($gradePartialRecovery);
                $partialResult = $partialRecovery->gradeCalculationFk->name == 'Média Semestral' ? $gradeResult['sem_rec_partial_'.$partialRecovery->semester] :  $gradeResult['rec_partial_'.$partialRecovery->order_partial_recovery];
                $partialRecoveryResult->addRecPartialResult($partialResult);
                $studentGradeResult->addPartialRecovery($partialRecoveryResult);

            }
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
 * @property array $partialRecoveryColumns
 * @property bool $isUnityConcept
 */
class StructClassromGradeResult
{
    private $students;
    private $concepts;
    private $isUnityConcept;
    private $semester;
    private $showSemAvarageColumn;
    private $type;
    private $unityColumns;
    private $partialRecoveryColumns;

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
    public function setSemester($semester)
    {
        $this->semester = $semester;

        return $this;
    }
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    public function setShowSemAvarageColumn($showSemAvarageColumn)
    {
        $this->showSemAvarageColumn = $showSemAvarageColumn;

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
     * Set the value of partialRecoveryColumns
     *
     * @return  self
     */
    public function setPartialRecoveryColumns($partialRecoveryColumns)
    {
        $this->partialRecoveryColumns = $partialRecoveryColumns;

        return $this;
    }

    /**
     * Get the value of partialRecoveryColumns
     */
    public function getPartialRecoveryColumns()
    {
        return $this->partialRecoveryColumns;
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
            'isUnityConcept' => $this->isUnityConcept,
            'partialRecoveryColumns' => $this->partialRecoveryColumns,
            'semester' => $this->semester,
            'showSemAvarageColumn' => $this->showSemAvarageColumn,
            'type' => $this->type
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
    private $semAvarage;
    private $situation;
    private $unities;
    private $partialRecoveries;
    private $enrollmentStatus;

    public function __construct($studentName, $enrollmentId)
    {
        $this->studentName = $studentName;
        $this->enrollmentId = $enrollmentId;
        $this->enrollmentStatus = StudentEnrollment::model()->findByPk($enrollmentId)->getCurrentStatus();
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
    public function setSemAvarage($gradesSemAvarage)
    {
        $this->semAvarage = $gradesSemAvarage;
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
    public function getpartialRecoveries()
    {
        return $this->partialRecoveries;
    }
    public function addPartialRecovery(GradePartialRecoveryResult $partialRecovery)
    {
        $this->partialRecoveries[] = $partialRecovery;
    }

    public function toArray(): array
    {
        return [
            'studentName' => $this->studentName,
            'enrollmentId' => $this->enrollmentId,
            'enrollmentStatus' => $this->enrollmentStatus,
            'finalMedia' => $this->finalMedia,
            'semAvarage' => $this->semAvarage,
            'situation' => $this->situation,
            'unities' => array_map(function (GradeUnityResult $unity) {
                return $unity->toArray();
            }, $this->unities),
            'partialRecoveries' => array_map(function (GradePartialRecoveryResult $partialRecovery) {
                return $partialRecovery->toArray();
            }, $this->partialRecoveries ?? []),
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

class GradePartialRecoveryResult {
    private $partialRecoveryName;
    private $grade;
    private $calculationName;
    private $recPartialResult;
    public function __construct($partialRecoveryName = null, $calculationName = null)
    {
        $this->partialRecoveryName = $partialRecoveryName;
        $this->calculationName = $calculationName;
    }
    public function addGrade($grade)
    {
        $this->grade = $grade;
    }
    public function addRecPartialResult($recPartialResult) {
        $this->recPartialResult = $recPartialResult;

    }
    public function toArray(): array
    {
        return [
            'partialRecoveryName' => $this->partialRecoveryName,
            'grade' => $this->grade,
            'recPartialResult' => $this->recPartialResult,
            'calculationName' => $this->calculationName
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
