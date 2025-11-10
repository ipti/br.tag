<?php

/**
 * This is the model class for table "student_enrollment".
 *
 * The followings are the available columns in table 'student_enrollment':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $student_inep_id
 * @property integer $student_fk
 * @property string $classroom_inep_id
 * @property integer $classroom_fk
 * @property string $enrollment_id
 * @property string $unified_class
 * @property integer $edcenso_stage_vs_modality_fk
 * @property integer $multi
 * @property integer $another_scholarization_place
 * @property integer $public_transport
 * @property integer $transport_responsable_government
 * @property integer $vehicle_type_van
 * @property integer $vehicle_type_microbus
 * @property integer $vehicle_type_bus
 * @property integer $vehicle_type_bike
 * @property integer $vehicle_type_animal_vehicle
 * @property integer $vehicle_type_other_vehicle
 * @property integer $vehicle_type_waterway_boat_5
 * @property integer $vehicle_type_waterway_boat_5_15
 * @property integer $vehicle_type_waterway_boat_15_35
 * @property integer $vehicle_type_waterway_boat_35
 * @property integer $vehicle_type_metro_or_train
 * @property integer $student_entry_form
 * @property integer $id
 * @property string $create_date
 * @property integer $hash
 * @property integer $current_stage_situation
 * @property integer $previous_stage_situation
 * @property integer $admission_type
 * @property integer $hash_classroom
 * @property integer $hash_student
 * @property string $date_cancellation_enrollment
 * @property integer $aee_cognitive_functions
 * @property integer $aee_autonomous_life
 * @property integer $aee_curriculum_enrichment
 * @property integer $aee_accessible_teaching
 * @property integer $aee_libras
 * @property integer $aee_portuguese
 * @property integer $aee_soroban
 * @property integer $aee_braille
 * @property integer $aee_mobility_techniques
 * @property integer $aee_caa
 * @property integer $aee_optical_nonoptical
 * @property string $observation
 * @property integer $daily_order
 * @property integer $status
 * @property string $transfer_date
 * @property integer $stage
 * @property integer $reenrollment
 * @property integer $sedspSync
 * @property string $class_transfer_date
 * @property string $school_readmission_date
 * @property string $enrollment_date
 * @property integer $current_enrollment
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 * @property Classroom $classroomFk
 * @property SchoolIdentification $schoolInepIdFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property GradeResults[] $gradeResults
 * @property ClassFaults[] $classFaults
 * @property Grade[] $grades
 * @property StudentEnrollmentHistory[] $studentEnrollmentHistories
 */
class StudentEnrollment extends AltActiveRecord
{
    public const STATUS_ACTIVE = 'MATRICULADO';
    public const STATUS_CANCELED = 'CANCELADO';
    public const STATUS_TRANSFERRED = 'TRANSFERIDO';
    public const STATUS_ABANDONED = 'DEIXOU DE FREQUENTAR';
    public const STATUS_RESTORED = 'REMANEJADO';
    public const STATUS_APPROVED = 'APROVADO';
    public const STATUS_APPROVEDBYCOUNCIL = 'APROVADO PELO CONSELHO';
    public const STATUS_DISAPPROVED = 'REPROVADO';
    public const STATUS_CONCLUDED = 'CONCLUINTE';
    public const STATUS_INDETERMINED = 'INDETERMINADO';
    public const STATUS_DEATH = 'OBITO';
    public const STATUS_ADVANCED = 'AVANÇADO';
    public const STATUS_REINTEGRATED = 'REINTEGRADO';

    public $schoolYear;
    public $sedspSync;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return StudentEnrollment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'student_enrollment';
    }

    public function behaviors()
    {
        // Define os comportamentos padrão
        $behaviors = [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ]
        ];

        // Verifica se o usuário está associado a uma escola
        if (isset(Yii::app()->user->school)) {
            // Adiciona o comportamento CAfterSaveBehavior se a escola estiver definida
            $behaviors['CAfterSaveBehavior'] = [
                'class' => 'application.behaviors.CAfterSaveBehavior',
                'schoolInepId' => Yii::app()->user->school,
            ];
        }

        return $behaviors;
    }

    public function TransportOptions()
    {
        return [
            'vehicle_type_van' => Yii::t('default', 'Vehicle Type Van'),
            'vehicle_type_microbus' => Yii::t('default', 'Vehicle Type Microbus'),
            'vehicle_type_bus' => Yii::t('default', 'Vehicle Type Bus'),
            'vehicle_type_bike' => Yii::t('default', 'Vehicle Type Bike'),
            'vehicle_type_animal_vehicle' => Yii::t('default', 'Vehicle Type Animal Vehicle'),
            'vehicle_type_other_vehicle' => Yii::t('default', 'Vehicle Type Other Vehicle'),
            'vehicle_type_waterway_boat_5' => Yii::t('default', 'Vehicle Type Waterway Boat 5'),
            'vehicle_type_waterway_boat_5_15' => Yii::t('default', 'Vehicle Type Waterway Boat 5 15'),
            'vehicle_type_waterway_boat_15_35' => Yii::t('default', 'Vehicle Type Waterway Boat 15 35'),
            'vehicle_type_waterway_boat_35' => Yii::t('default', 'Vehicle Type Waterway Boat 35'),
            'vehicle_type_metro_or_train' => Yii::t('default', 'Vehicle Type Metro Or Train')
        ];
    }

    public function validateMultiply()
    {
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['school_inep_id_fk, student_fk', 'required'],
            ['daily_order, student_fk, classroom_fk, unified_class, edcenso_stage_vs_modality_fk, another_scholarization_place, public_transport, transport_responsable_government, vehicle_type_van, vehicle_type_microbus, vehicle_type_bus, vehicle_type_bike, vehicle_type_animal_vehicle, vehicle_type_other_vehicle, vehicle_type_waterway_boat_5, vehicle_type_waterway_boat_5_15, vehicle_type_waterway_boat_15_35, vehicle_type_waterway_boat_35, vehicle_type_metro_or_train, student_entry_form, current_stage_situation, previous_stage_situation, admission_type, status, aee_cognitive_functions, aee_autonomous_life, aee_curriculum_enrichment, aee_accessible_teaching, aee_libras, aee_portuguese, aee_soroban, aee_braille, aee_mobility_techniques, aee_caa, aee_optical_nonoptical, current_enrollment', 'numerical', 'integerOnly' => true],
            ['register_type', 'length', 'max' => 2],
            ['school_inep_id_fk', 'length', 'max' => 8],
            ['student_inep_id, classroom_inep_id, enrollment_id', 'length', 'max' => 12],
            ['hash', 'length', 'max' => 40],
            ['enrollment_id', 'validateMultiply'],
            ['observation', 'length', 'max' => 200],
            ['reenrollment, class_transfer_date, school_readmission_date, enrollment_date', 'safe'],
            ['register_type, school_inep_id_fk, student_inep_id, student_fk, classroom_inep_id, classroom_fk, enrollment_id, unified_class, edcenso_stage_vs_modality_fk, another_scholarization_place, public_transport, transport_responsable_government, vehicle_type_van, vehicle_type_microbus, vehicle_type_bus, vehicle_type_bike, vehicle_type_animal_vehicle, vehicle_type_other_vehicle, vehicle_type_waterway_boat_5, vehicle_type_waterway_boat_5_15, vehicle_type_waterway_boat_15_35, vehicle_type_waterway_boat_35, vehicle_type_metro_or_train, student_entry_form, id, create_date, fkid, current_stage_situation, previous_stage_situation, admission_type, status, aee_cognitive_functions, aee_autonomous_life, aee_curriculum_enrichment, aee_accessible_teaching, aee_libras, aee_portuguese, aee_soroban, aee_braille, aee_mobility_techniques, class_transfer_date, school_readmission_date, aee_caa, aee_optical_nonoptical, enrollment_date, current_enrollment', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.

        return [
            'studentFk' => [self::BELONGS_TO, 'StudentIdentification', 'student_fk'],
            'classroomFk' => [self::BELONGS_TO, 'Classroom', 'classroom_fk'],
            'schoolInepIdFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'],
            'edcensoStageVsModalityFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
            'gradeResults' => [self::HAS_MANY, 'GradeResults', 'enrollment_fk'],
            'classFaults' => [self::HAS_MANY, 'ClassFaults', 'student_fk'],
            'grades' => [self::HAS_MANY, 'Grade', 'enrollment_fk'],
            'studentEnrollmentHistories' => [self::HAS_MANY, 'StudentEnrollmentHistory', 'student_enrollment_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'register_type' => Yii::t('default', 'Register Type'),
            'school_inep_id_fk' => Yii::t('default', 'School Inep Id Fk'),
            'student_inep_id' => Yii::t('default', 'Student Inep'),
            'student_fk' => Yii::t('default', 'Student Fk'),
            'classroom_inep_id' => Yii::t('default', 'Classroom Inep'),
            'classroom_fk' => Yii::t('default', 'Classroom Fk'),
            'enrollment_id' => Yii::t('default', 'Enrollment'),
            'unified_class' => Yii::t('default', 'Unified Class'),
            'edcenso_stage_vs_modality_fk' => Yii::t('default', 'Edcenso Stage Vs Modality Fk'),
            'another_scholarization_place' => Yii::t('default', 'Another Scholarization Place'),
            'public_transport' => Yii::t('default', 'Public Transport') . ' *',
            'transport_responsable_government' => Yii::t('default', 'Transport Responsable Government') . ' *',
            'vehicle_type_van' => Yii::t('default', 'Vehicle Type Van'),
            'vehicle_type_microbus' => Yii::t('default', 'Vehicle Type Microbus'),
            'vehicle_type_bus' => Yii::t('default', 'Vehicle Type Bus'),
            'vehicle_type_bike' => Yii::t('default', 'Vehicle Type Bike'),
            'vehicle_type_animal_vehicle' => Yii::t('default', 'Vehicle Type Animal Vehicle'),
            'vehicle_type_other_vehicle' => Yii::t('default', 'Vehicle Type Other Vehicle'),
            'vehicle_type_waterway_boat_5' => Yii::t('default', 'Vehicle Type Waterway Boat 5'),
            'vehicle_type_waterway_boat_5_15' => Yii::t('default', 'Vehicle Type Waterway Boat 5 15'),
            'vehicle_type_waterway_boat_15_35' => Yii::t('default', 'Vehicle Type Waterway Boat 15 35'),
            'vehicle_type_waterway_boat_35' => Yii::t('default', 'Vehicle Type Waterway Boat 35'),
            'vehicle_type_metro_or_train' => Yii::t('default', 'Vehicle Type Metro Or Train'),
            'student_entry_form' => Yii::t('default', 'Student Entry Form'),
            'school_year' => Yii::t('default', 'School Year'),
            'id' => Yii::t('default', 'ID'),
            'create_date' => Yii::t('default', 'Create Time'),
            'current_stage_situation' => Yii::t('default', 'Current stage situation'),
            'previous_stage_situation' => Yii::t('default', 'Previous stage situation'),
            'admission_type' => Yii::t('default', 'Admission type'),
            'status' => Yii::t('default', 'Status'),
            'aee_cognitive_functions' => Yii::t('default', 'Aee Cognitive Functions'),
            'aee_autonomous_life' => Yii::t('default', 'Aee Autonomous Life'),
            'aee_curriculum_enrichment' => Yii::t('default', 'Aee Curriculum Enrichment'),
            'aee_accessible_teaching' => Yii::t('default', 'Aee Accessible Teaching'),
            'aee_libras' => Yii::t('default', 'Aee Libras'),
            'aee_portuguese' => Yii::t('default', 'Aee Portuguese'),
            'aee_soroban' => Yii::t('default', 'Aee Soroban'),
            'aee_braille' => Yii::t('default', 'Aee Braille'),
            'aee_mobility_techniques' => Yii::t('default', 'Aee Mobility Techniques'),
            'aee_caa' => Yii::t('default', 'Aee Caa'),
            'aee_optical_nonoptical' => Yii::t('default', 'Aee Optical Nonoptical'),
            'observation' => Yii::t('default', 'Observation'),
            'daily_order' => Yii::t('default', 'daily_order'),
            'school-identifications' => Yii::t('default', 'School Identifications'),
            'transfer_date' => Yii::t('default', 'Transfer Date'),
            'class_transfer_date' => Yii::t('default', 'Class Transfer Date'),
            'school_readmission_date' => Yii::t('default', 'School Readmission Date'),
            'enrollment_date' => Yii::t('default', 'Enrollment Date'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function getEnrollmentPastYear()
    {
        $criteria = new CDbCriteria();
        $criteria->with = ['studentFk', 'classroomFk'];
        $criteria->compare('student_fk', $this->student_fk);
        $criteria->compare('classroomFk.school_year', $this->classroomFk->school_year - 1);
        return @StudentEnrollment::model()->find($criteria);
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();
        $criteria->with = ['studentFk', 'classroomFk'];
        $criteria->together = true;
        $criteria->compare('enrollment_id', $this->enrollment_id, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('classroomFk.school_year', Yii::app()->user->year);
        $criteria->compare('class_transfer_date', $this->class_transfer_date);
        $criteria->compare('school_readmission_date', $this->school_readmission_date);
        $criteria->compare('enrollment_date', $this->enrollment_date);
        $school = Yii::app()->user->school;
        $criteria->compare('t.school_inep_id_fk', $school);
        $criteria->addCondition('studentFk.name like "%' . $this->student_fk . '%"');
        $criteria->addCondition('classroomFk.name like "%' . $this->classroom_fk . '%"');

        return new CActiveDataProvider(
            $this,
            [
                'criteria' => $criteria,
                'sort' => [
                    'attributes' => [
                        'studentFk.name' => [
                            'asc' => 'studentFk.name',
                            'desc' => 'studentFk.name DESC',
                        ],
                        'classroomFk.name' => [
                            'asc' => 'classroomFk.name',
                            'desc' => 'classroomFk.name DESC',
                        ],
                        'classroomFk.school_year' => [
                            'asc' => 'classroomFk.school_year',
                            'desc' => 'classroomFk.school_year DESC',
                        ],
                        '*',
                        // Make all other columns sortable, too
                    ],
                    'defaultOrder' => [
                        'studentFk.name' => CSort::SORT_ASC
                    ],
                ],
                'pagination' => false
            ]
        );
    }

    public function totalStudentEnrolmentFrequency($initialMonth = null, $finalMonth = null)
    {
        $classroom = $this->classroomFk;

        $isMinorEducation = TagUtils::isStageMinorEducation($classroom->edcensoStageVsModalityFk->edcenso_associated_stage_id);

        if (!$isMinorEducation) {
            //Frequência fundamental Maior
            return $this->frequencyMiddleSchool($initialMonth, $finalMonth, $classroom);
        } else {
            //Frequência fundamental Menor

            return $this->frequencyMinorEducation($initialMonth, $finalMonth, $classroom);
        }
    }

    private function frequencyMiddleSchool($initialMonth = null, $finalMonth = null, $classroom)
    {
        $criteriaTotalClasses = new CDbCriteria();
        $criteriaTotalClasses->alias = 's';

        $criteriaTotalFaults = new CDbCriteria();
        $criteriaTotalFaults->alias = 'cf';
        $criteriaTotalFaults->join = 'INNER JOIN schedule s ON s.id = cf.schedule_fk';

        //frequencia em um periodo
        if ($initialMonth != null && $finalMonth != null) {
            $criteriaTotalClasses->condition = 's.month >= :initialMonth AND s.month <= :finalMonth AND s.unavailable = 0 AND s.classroom_fk = :classroom';
            $criteriaTotalClasses->params = [':initialMonth' => $initialMonth, ':finalMonth' => $finalMonth, ':classroom' => $classroom->id];

            $criteriaTotalFaults->condition = 'se.id = :enrollment AND cf.justification IS NULL AND s.month >= :initialMonth AND s.month <= :finalMonth AND s.unavailable = 0';
            $criteriaTotalFaults->params = [':enrollment' => $this->id, ':initialMonth' => $initialMonth, ':finalMonth' => $finalMonth];
        } else {
            $criteriaTotalClasses->condition = 's.unavailable = 0 AND s.classroom_fk = :classroom';
            $criteriaTotalClasses->params = [':classroom' => $classroom->id];

            $criteriaTotalFaults->condition = 'se.id = :enrollment AND cf.justification IS NULL';
            $criteriaTotalFaults->params = [':enrollment' => $this->id];
        }

        $totalClasses = Schedule::model()->count($criteriaTotalClasses);

        $criteriaTotalFaults->join .= ' INNER JOIN student_identification si ON si.id = cf.student_fk
                       INNER JOIN student_enrollment se ON se.student_fk = si.id';
        $totalFaults = ClassFaults::model()->count($criteriaTotalFaults);

        return round((($totalClasses - $totalFaults) / ($totalClasses ?: 1)) * 100);
    }

    private function frequencyMinorEducation($initialMonth = null, $finalMonth = null, $classroom)
    {
        $criteriaTotalClasses = new CDbCriteria();
        $criteriaTotalClasses->alias = 's';
        $criteriaTotalClasses->select = 's.day, s.month, s.year';
        $criteriaTotalClasses->group = 's.year, s.month, s.day';

        $criteriaTotalFaults = new CDbCriteria();
        $criteriaTotalFaults->alias = 'cf';
        $criteriaTotalFaults->select = 's.day, s.month, s.year';
        $criteriaTotalFaults->group = 's.day, s.month, s.year';

        $criteriaTotalFaults->join = '
                INNER JOIN student_enrollment se ON se.student_fk = cf.student_fk
                INNER JOIN schedule s ON s.id = cf.schedule_fk
            ';

        //frequencia em um periodo
        if ($initialMonth != null && $finalMonth != null) {
            $criteriaTotalClasses->condition = 'classroom_fk = :classroomId AND  s.unavailable = 0 AND s.month >= :initialMonth AND s.month <= :finalMonth';
            $criteriaTotalClasses->params = [':classroomId' => $classroom->id, ':initialMonth' => $initialMonth, ':finalMonth' => $finalMonth];

            $criteriaTotalFaults->condition = 'se.id = :enrollmentId AND s.classroom_fk = :classroomId  AND cf.justification IS NULL AND s.unavailable = 0 AND s.month >= :initialMonth AND s.month <= :finalMonth';
            $criteriaTotalFaults->params = [':enrollmentId' => $this->id, ':classroomId' => $classroom->id, ':initialMonth' => $initialMonth, ':finalMonth' => $finalMonth];
        } else {
            $criteriaTotalClasses->condition = 'classroom_fk = :classroomId AND s.unavailable = 0';
            $criteriaTotalClasses->params = [':classroomId' => $classroom->id];

            $criteriaTotalFaults->condition = 'se.id = :enrollmentId AND s.classroom_fk = :classroomId AND cf.justification IS NULL AND s.unavailable = 0';
            $criteriaTotalFaults->params = [':enrollmentId' => $this->id, ':classroomId' => $classroom->id];
        }

        $totalClasses = Schedule::model()->count($criteriaTotalClasses);

        $totalFaults = ClassFaults::model()->count($criteriaTotalFaults);

        return round((($totalClasses - $totalFaults) / ($totalClasses ?: 1)) * 100);
    }

    public function studentEnrolmentFrequencyPerDiscipline($disciplineId)
    {
        $classroom = $this->classroomFk;
        $criteriaTotalClasses = new CDbCriteria();
        $criteriaTotalClasses->alias = 's';
        $criteriaTotalClasses->condition = 's.unavailable = 0 AND s.classroom_fk = :classroom AND s.discipline_fk = :discipline';
        $criteriaTotalClasses->params = [':classroom' => $classroom->id, ':discipline' => $disciplineId];
        $totalClasses = Schedule::model()->count($criteriaTotalClasses);

        $criteria = new CDbCriteria();
        $criteria->alias = 'cf';
        $criteria->join = 'INNER JOIN student_identification si ON si.id = cf.student_fk
                   INNER JOIN student_enrollment se ON se.student_fk = si.id
                   INNER JOIN schedule s ON s.id = cf.schedule_fk';
        $criteria->condition = 'se.id = :enrollmentId and s.classroom_fk = :classroomId AND s.discipline_fk = :disciplineId AND cf.justification IS NULL AND s.unavailable = 0';
        $criteria->params = [':enrollmentId' => $this->id, ':classroomId' => $classroom->id, ':disciplineId' => $disciplineId];
        $totalFaults = ClassFaults::model()->count($criteria);

        return round((($totalClasses - $totalFaults) / ($totalClasses ?: 1)) * 100);
    }

    public function alreadyExists()
    {
        $sql = 'SELECT count(student_fk) as qtd FROM student_enrollment WHERE student_fk = :student_fk  AND classroom_fk = :classroom_fk';

        $count = Yii::app()->db->createCommand($sql)
            ->bindParam(':student_fk', $this->student_fk)
            ->bindParam(':classroom_fk', $this->classroom_fk)
            ->queryRow();
        return $count['qtd'] > 0;
    }

    public function getDailyOrder()
    {
        $sql = 'SELECT count(student_fk) as qtd FROM student_enrollment WHERE classroom_fk = :classroom_fk';
        $count = Yii::app()->db->createCommand($sql)
            ->bindParam(':classroom_fk', $this->classroom_fk)
            ->queryRow();

        return $count['qtd'] + 1;
    }

    /**
     * Get all faults by discipline
     *
     * @param integer $disciplineId
     * @return ClassFaults[]
     */
    public function getFaultsByDiscipline($disciplineId)
    {
        $faults = [];
        foreach ($this->classFaults as $fault) {
            $class = $fault->scheduleFk;
            $discipline = $class->disciplineFk;
            if ($discipline->id == $disciplineId) {
                array_push($faults, $fault);
            }
        }
        return $faults;
    }

    public function countFaultsDiscipline($disciplineId = null): int
    {
        $classroomId = $this->classroom_fk;
        $studentId = $this->student_fk;
        $isMinorEducation = TagUtils::isStageMinorEducation($this->classroomFk->edcensoStageVsModalityFk->edcenso_associated_stage_id);

        $command = Yii::app()->db->createCommand()
            ->from('class_faults cf')
            ->join('schedule s', 'cf.schedule_fk = s.id')
            ->where('student_fk = :studentId and s.classroom_fk = :classroomId and cf.justification IS NULL')
            ->select('count(DISTINCT CONCAT(s.`year`, s.`month`, s.`day`))')
            ->bindValues([
                ':classroomId' => $classroomId,
                ':studentId' => $studentId
            ]);

        if (!$isMinorEducation) {
            $command = Yii::app()->db->createCommand()
                ->select('COUNT(1)')
                ->from('schedule t')
                ->join(
                    '(SELECT s1.classroom_fk, s1.`day`, s1.`month`, s1.`year`, s1.discipline_fk
                        FROM class_faults cf
                        JOIN schedule s1 ON cf.schedule_fk = s1.id
                        WHERE s1.classroom_fk = :classroomId
                        AND cf.student_fk = :studentId
                        AND cf.justification IS NULL
                        AND s1.discipline_fk = :disciplineId) sf',
                    ' t.classroom_fk = sf.classroom_fk
                        AND sf.`month` = t.month
                        AND sf.`day` = t.`day`
                        AND sf.discipline_fk = t.discipline_fk'
                )
                ->bindValues([
                    ':classroomId' => $classroomId,
                    ':studentId' => $studentId,
                    ':disciplineId' => $disciplineId,
                ]);
        }

        return $command->queryScalar() ?? 0;
    }

    public function getFaultsByExam($exam)
    {
        /* @var $schoolConfiguration SchoolConfiguration */
        $schoolConfiguration = SchoolConfiguration::model()->findByAttributes(['school_inep_id_fk' => yii::app()->user->school]);
        $faults = [];
        switch ($exam) {
            case 1:
                $initial = new DateTime('01/01/' . yii::app()->user->year);
                $final = new DateTime($schoolConfiguration->exam1);
                break;
            case 2:
                $initial = new DateTime($schoolConfiguration->exam1);
                $final = new DateTime($schoolConfiguration->exam2);
                break;
            case 3:
                $initial = new DateTime($schoolConfiguration->exam2);
                $final = new DateTime($schoolConfiguration->exam3);
                break;
            case 4:
                $initial = new DateTime($schoolConfiguration->exam3);
                $final = new DateTime($schoolConfiguration->exam4);
                break;
            default:
                return [];
        }
        foreach ($this->classFaults as $fault) {
            $date = new DateTime($fault->scheduleFk->day . '-' . $fault->scheduleFk->month . '-' . yii::app()->user->year);
            if ($date > $initial && $date <= $final) {
                array_push($faults, $fault);
            }
        }
        return $faults;
    }

    public static function getFileInformation($enrollment_id)
    {
        $sql = 'SELECT * FROM studentsfile WHERE enrollment_id = :enrollment_id';
        return Yii::app()->db->createCommand($sql)
            ->bindParam(':enrollment_id', $enrollment_id)
            ->queryRow();
    }

    public function getResultGrade($disciplines)
    {
        $result = [];
        $grades = $this->grades;
        foreach ($grades as $grade) {
            if (isset($disciplines[$grade->discipline_fk])) {
                $frequencyAndMean = FrequencyAndMeanByDiscipline::model()->findByAttributes([
                    'enrollment_fk' => $grade->enrollment_fk,
                    'discipline_fk' => $grade->discipline_fk
                ]);

                $result[$grade->discipline_fk] = ['final_average' => $frequencyAndMean->final_average, 'frequency' => $frequencyAndMean->frequency];
            }
        }
        ksort($result);
        return $result;
    }

    public function getSheetGrade()
    {
        $disciplineCategory = [
            'base' => [
                3 => 'Matemática',
                6 => 'Português',
                5 => 'Ciências',
                13 => 'Geografia',
                12 => 'História',
                10 => 'Artes',
                11 => 'Educação Física',
                26 => 'Ensino Religioso',
            ]
        ];

        $disciplines = ClassroomController::classroomDisciplineLabelArray();

        $disciplineCategory['diversified'] = array_diff_assoc($disciplines, $disciplineCategory['base']);

        $evaluations = [1 => [], 2 => [], 3 => [], 4 => []];

        $recovery = [1 => [], 2 => [], 3 => [], 4 => [], 'final' => []];

        $average = [1 => [], 2 => [], 'final' => [], 'year' => []];

        $workHours = [1 => null, 2 => null, 3 => null, 4 => null, 'final' => null];

        $workDays = [1 => null, 2 => null, 3 => null, 4 => null, 'final' => null];

        $frequency = [];

        $absences = [1 => null, 2 => null, 3 => null, 4 => null, 'base' => [], 'diversified' => [], 'percent' => null];

        $workDaysByDiscipline = ['base' => [], 'diversified' => []];

        $grades = $this->grades;
        foreach ($grades as $grade) {
            if (isset($disciplines[$grade->discipline_fk])) {
                $frequencyAndMean = FrequencyAndMeanByDiscipline::model()->findByAttributes([
                    'enrollment_fk' => $grade->enrollment_fk,
                    'discipline_fk' => $grade->discipline_fk
                ]);

                $disciplineType = '';
                if (isset($disciplineCategory['base'][$grade->discipline_fk])) {
                    $disciplineType = 'base';
                } else {
                    $disciplineType = 'diversified';
                }

                $evaluations[1][$disciplineType][$grade->discipline_fk] = is_null($grade->grade1) ? '' : number_format($grade->grade1, 2, ',', '');
                $evaluations[2][$disciplineType][$grade->discipline_fk] = is_null($grade->grade2) ? '' : number_format($grade->grade2, 2, ',', '');
                $evaluations[3][$disciplineType][$grade->discipline_fk] = is_null($grade->grade3) ? '' : number_format($grade->grade3, 2, ',', '');
                $evaluations[4][$disciplineType][$grade->discipline_fk] = is_null($grade->grade4) ? '' : number_format($grade->grade4, 2, ',', '');

                $recovery[1][$disciplineType][$grade->discipline_fk] = is_null($grade->recovery_grade1) ? '' : number_format($grade->recovery_grade1, 2, ',', '');
                $recovery[2][$disciplineType][$grade->discipline_fk] = is_null($grade->recovery_grade2) ? '' : number_format($grade->recovery_grade2, 2, ',', '');
                $recovery[3][$disciplineType][$grade->discipline_fk] = is_null($grade->recovery_grade3) ? '' : number_format($grade->recovery_grade3, 2, ',', '');
                $recovery[4][$disciplineType][$grade->discipline_fk] = is_null($grade->recovery_grade4) ? '' : number_format($grade->recovery_grade4, 2, ',', '');
                $recovery['final'][$disciplineType][$grade->discipline_fk] = number_format($grade->recovery_final_grade, 2, ',', '');

                $frequency[$disciplineType][$grade->discipline_fk] = number_format($frequencyAndMean->frequency, 2, ',', '');
                $absences['percent'][$disciplineType][$grade->discipline_fk] = $frequencyAndMean->absences;

                $absences[$disciplineType][$grade->discipline_fk] = count($this->getFaultsByDiscipline($grade->discipline_fk));

                $average[1][$disciplineType][$grade->discipline_fk] = number_format(($grade->grade1 + $grade->grade2) / 2, 2, ',', '');
                $average[2][$disciplineType][$grade->discipline_fk] = number_format(($grade->grade3 + $grade->grade4) / 2, 2, ',', '');

                $average['final'][$disciplineType][$grade->discipline_fk] = number_format($frequencyAndMean->final_average, 2, ',', '');
                $average['year'][$disciplineType][$grade->discipline_fk] = number_format($frequencyAndMean->annual_average, 2, ',', '');
                $classroom = $this->classroomFk;

                $workDaysByDiscipline[$disciplineType][$grade->discipline_fk] = $classroom->getWorkingDaysByDiscipline($grade->discipline_fk);

                if (is_null($workDays[1])) {
                    $workDays[1] = $classroom->getSchoolDaysByExam(1);
                    $workDays[2] = $classroom->getSchoolDaysByExam(2);
                    $workDays[3] = $classroom->getSchoolDaysByExam(3);
                    $workDays[4] = $classroom->getSchoolDaysByExam(4);

                    $workHours[1] = $classroom->getWorkingHoursByExam(1);
                    $workHours[2] = $classroom->getWorkingHoursByExam(2);
                    $workHours[3] = $classroom->getWorkingHoursByExam(3);
                    $workHours[4] = $classroom->getWorkingHoursByExam(4);
                }

                if (is_null($absences[1])) {
                    $absences[1] = count($this->getFaultsByExam(1));
                    $absences[2] = count($this->getFaultsByExam(2));
                    $absences[3] = count($this->getFaultsByExam(3));
                    $absences[4] = count($this->getFaultsByExam(4));
                }
            }
        }

        $sort = function (&$array) {
            foreach ($array as $key => $value) {
                if (isset($array[$key]['base'])) {
                    ksort($array[$key]['base']);
                }
                if (isset($array[$key]['diversified'])) {
                    ksort($array[$key]['diversified']);
                }
            }
        };

        $fill = function (&$evaluations, &$recovery, &$frequency, &$absences, &$average, &$workDaysByDiscipline, $disciplineType) {
            $evaluations[1][$disciplineType][] = null;
            $evaluations[2][$disciplineType][] = null;
            $evaluations[3][$disciplineType][] = null;
            $evaluations[4][$disciplineType][] = null;

            $recovery[1][$disciplineType][] = null;
            $recovery[2][$disciplineType][] = null;
            $recovery[3][$disciplineType][] = null;
            $recovery[4][$disciplineType][] = null;
            $recovery['final'][$disciplineType][] = null;

            $frequency[$disciplineType][] = null;

            $absences['percent'][$disciplineType] = null;
            $absences[$disciplineType][] = null;

            $average[1][$disciplineType][] = null;
            $average[2][$disciplineType][] = null;
            $average['final'][$disciplineType][] = null;
            $average['year'][$disciplineType][] = null;

            $workDaysByDiscipline[$disciplineType][] = null;
        };

        $sort($evaluations);
        $sort($recovery);
        $sort($average);
        $sort($frequency);
        $sort($absences);
        $disciplineFilter['base'] = array_intersect_key($disciplineCategory['base'], $evaluations[1]['base']);
        $disciplineFilter['diversified'] = array_intersect_key($disciplineCategory['diversified'], $evaluations[1]['diversified']);
        ksort($disciplineFilter['base']);
        ksort($disciplineFilter['diversified']);

        // condição adicionada para manter a quantidade de disciplinas padronizada
        if (count($disciplineFilter['base']) + count($disciplineFilter['diversified']) < 13) {
            while (count($disciplineFilter['base']) < 8) {
                $fill($evaluations, $recovery, $frequency, $absences, $average, $workDaysByDiscipline, 'base');
                $disciplineFilter['base'][] = null;
            }
            while (count($disciplineFilter['diversified']) < 4) {
                $fill($evaluations, $recovery, $frequency, $absences, $average, $workDaysByDiscipline, 'diversified');
                $disciplineFilter['diversified'][] = null;
            }
        }

        return [
            'disciplines' => $disciplineFilter,
            'evaluations' => $evaluations,
            'recovery' => $recovery,
            'average' => $average,
            'work_hours' => $workHours,
            'work_days' => $workDays,
            'work_days_discipline' => $workDaysByDiscipline,
            'frequency' => $frequency,
            'absences' => $absences
        ];
    }

    public static function getListStatus()
    {
        return [
            '1' => StudentEnrollment::STATUS_ACTIVE,
            '2' => StudentEnrollment::STATUS_TRANSFERRED,
            '3' => StudentEnrollment::STATUS_CANCELED,
            '4' => StudentEnrollment::STATUS_ABANDONED,
            '5' => StudentEnrollment::STATUS_RESTORED,
            '6' => StudentEnrollment::STATUS_APPROVED,
            '7' => StudentEnrollment::STATUS_APPROVEDBYCOUNCIL,
            '8' => StudentEnrollment::STATUS_DISAPPROVED,
            '9' => StudentEnrollment::STATUS_CONCLUDED,
            '10' => StudentEnrollment::STATUS_INDETERMINED,
            '11' => StudentEnrollment::STATUS_DEATH,
            '12' => StudentEnrollment::STATUS_ADVANCED,
            '13' => StudentEnrollment::STATUS_REINTEGRATED
        ];

    }

    public function getCurrentStatus()
    {
        $status = [
            null => '',
            '1' => StudentEnrollment::STATUS_ACTIVE,
            '2' => StudentEnrollment::STATUS_TRANSFERRED,
            '3' => StudentEnrollment::STATUS_CANCELED,
            '4' => StudentEnrollment::STATUS_ABANDONED,
            '5' => StudentEnrollment::STATUS_RESTORED,
            '6' => StudentEnrollment::STATUS_APPROVED,
            '7' => StudentEnrollment::STATUS_APPROVEDBYCOUNCIL,
            '8' => StudentEnrollment::STATUS_DISAPPROVED,
            '9' => StudentEnrollment::STATUS_CONCLUDED,
            '10' => StudentEnrollment::STATUS_INDETERMINED,
            '11' => StudentEnrollment::STATUS_DEATH,
            '12' => StudentEnrollment::STATUS_ADVANCED,
            '13' => StudentEnrollment::STATUS_REINTEGRATED
        ];

        return $status[$this->status];
    }

    public function isActive()
    {
        $refActiveStatus = [
            '1',
            '8',
            '10',
            '6',
            '5',
            null
        ];
        $stages = new CList($refActiveStatus, true);
        return $stages->contains($this->status . '');
    }

    public static function getStatusId($status)
    {
        $statusList = [
            StudentEnrollment::STATUS_ACTIVE => '1',
            StudentEnrollment::STATUS_TRANSFERRED => '2',
            StudentEnrollment::STATUS_CANCELED => '3',
            StudentEnrollment::STATUS_ABANDONED => '4',
            StudentEnrollment::STATUS_RESTORED => '5',
            StudentEnrollment::STATUS_APPROVED => '6',
            StudentEnrollment::STATUS_APPROVEDBYCOUNCIL => '7',
            StudentEnrollment::STATUS_DISAPPROVED => '8',
            StudentEnrollment::STATUS_CONCLUDED => '9',
            StudentEnrollment::STATUS_INDETERMINED => '10',
            StudentEnrollment::STATUS_DEATH => '11',
            StudentEnrollment::STATUS_ADVANCED => '12',
            StudentEnrollment::STATUS_REINTEGRATED => '13'
        ];

        return $statusList[$status];
    }

    public function getStudentClassAnottations($schedule, $students)
    {
        $studentArray = [];
        foreach ($students as $student) {
            $studentData = [
                'id' => $student['id'],
                'name' => $student['name'],
                'diary' => ''
            ];

            foreach ($schedule->classDiaries as $classDiary) {
                if ($classDiary->student_fk == $student['id']) {
                    $studentData['diary'] = $classDiary->diary;
                }
            }
            $studentArray[] = $studentData;
        }

        return $studentArray;
    }
}
