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
 * @property integer $unified_class
 * @property integer $edcenso_stage_vs_modality_fk
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
 * @property string $fkid
 * @property string $school_admission_date
 * @property integer $current_stage_situation
 * @property integer $previous_stage_situation
 * @property integer $admission_type
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 * @property Classroom $classroomFk
 * @property SchoolIdentification $schoolInepIdFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property ClassFaults[] $classFaults
 * @property Grade[] $grades
 */
class StudentEnrollment extends CActiveRecord
{

    public $school_year;

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
        if (isset(Yii::app()->user->school)) {
            return [
                'afterSave' => [
                    'class' => 'application.behaviors.CAfterSaveBehavior',
                    'schoolInepId' => Yii::app()->user->school,
                ],
            ];
        } else {
            return [];
        }
    }
    public function TransportOptions(){
        return array('vehicle_type_van' => Yii::t('default', 'Vehicle Type Van'),
            'vehicle_type_microbus' => Yii::t('default', 'Vehicle Type Microbus'),
            'vehicle_type_bus' => Yii::t('default', 'Vehicle Type Bus'),
            'vehicle_type_bike' => Yii::t('default', 'Vehicle Type Bike'),
            'vehicle_type_animal_vehicle' => Yii::t('default', 'Vehicle Type Animal Vehicle'),
            'vehicle_type_other_vehicle' => Yii::t('default', 'Vehicle Type Other Vehicle'),
            'vehicle_type_waterway_boat_5' => Yii::t('default', 'Vehicle Type Waterway Boat 5'),
            'vehicle_type_waterway_boat_5_15' => Yii::t('default', 'Vehicle Type Waterway Boat 5 15'),
            'vehicle_type_waterway_boat_15_35' => Yii::t('default', 'Vehicle Type Waterway Boat 15 35'),
            'vehicle_type_waterway_boat_35' => Yii::t('default', 'Vehicle Type Waterway Boat 35'),
            'vehicle_type_metro_or_train' => Yii::t('default', 'Vehicle Type Metro Or Train'));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_inep_id_fk, student_fk, classroom_fk, create_date', 'required'),
            array('student_fk, classroom_fk, unified_class, edcenso_stage_vs_modality_fk, another_scholarization_place, public_transport, transport_responsable_government, vehicle_type_van, vehicle_type_microbus, vehicle_type_bus, vehicle_type_bike, vehicle_type_animal_vehicle, vehicle_type_other_vehicle, vehicle_type_waterway_boat_5, vehicle_type_waterway_boat_5_15, vehicle_type_waterway_boat_15_35, vehicle_type_waterway_boat_35, vehicle_type_metro_or_train, student_entry_form, current_stage_situation, previous_stage_situation, admission_type', 'numerical', 'integerOnly'=>true),
            array('register_type', 'length', 'max'=>2),
            array('school_inep_id_fk', 'length', 'max'=>8),
            array('student_inep_id, classroom_inep_id, enrollment_id', 'length', 'max'=>12),
            array('fkid', 'length', 'max'=>40),
            array('school_admission_date', 'length', 'max'=>10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('register_type, school_inep_id_fk, student_inep_id, student_fk, classroom_inep_id, classroom_fk, enrollment_id, unified_class, edcenso_stage_vs_modality_fk, another_scholarization_place, public_transport, transport_responsable_government, vehicle_type_van, vehicle_type_microbus, vehicle_type_bus, vehicle_type_bike, vehicle_type_animal_vehicle, vehicle_type_other_vehicle, vehicle_type_waterway_boat_5, vehicle_type_waterway_boat_5_15, vehicle_type_waterway_boat_15_35, vehicle_type_waterway_boat_35, vehicle_type_metro_or_train, student_entry_form, id, create_date, fkid, school_admission_date, current_stage_situation, previous_stage_situation, admission_type', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'studentFk' => array(self::BELONGS_TO, 'StudentIdentification', 'student_fk'),
            'classroomFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_fk'),
            'schoolInepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'),
            'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
            'classFaults' => array(self::HAS_MANY, 'ClassFaults', 'student_fk'),
            'grades' => array(self::HAS_MANY, 'Grade', 'enrollment_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
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
            'public_transport' => Yii::t('default', 'Public Transport'),
            'transport_responsable_government' => Yii::t('default', 'Transport Responsable Government'),
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
            'school_admission_date' => Yii::t('default', 'School admission date'),
            'admission_type' => Yii::t('default', 'Admission type')

        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function getEnrollmentPastYear() {
        $criteria=new CDbCriteria;
        $criteria->with = array('studentFk', 'classroomFk');
        $criteria->compare('student_fk',$this->student_fk);
        $criteria->compare( 'classroomFk.school_year', $this->classroomFk->school_year-1);
        return @StudentEnrollment::model()->find($criteria);
    }
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->with = array('studentFk', 'classroomFk');
        $criteria->together = true;
        $criteria->compare('enrollment_id', $this->enrollment_id, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('classroomFk.school_year', Yii::app()->user->year);
        $school = Yii::app()->user->school;
        $criteria->compare('t.school_inep_id_fk', $school);
        $criteria->addCondition('studentFk.name like "%' . $this->student_fk . '%"');
        $criteria->addCondition('classroomFk.name like "%' . $this->classroom_fk . '%"');


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'studentFk.name' => array(
                        'asc' => 'studentFk.name',
                        'desc' => 'studentFk.name DESC',
                    ),
                    'classroomFk.name' => array(
                        'asc' => 'classroomFk.name',
                        'desc' => 'classroomFk.name DESC',
                    ),
                    'classroomFk.school_year' => array(
                        'asc' => 'classroomFk.school_year',
                        'desc' => 'classroomFk.school_year DESC',
                    ),
                    '*', // Make all other columns sortable, too
                ),
                'defaultOrder' => array(
                    'studentFk.name' => CSort::SORT_ASC
                ),
            ),
            'pagination' => array(
                'pageSize' => 12,
            ),
        ));
    }


    /**
     * Get all faults by discipline
     *
     * @param integer $disciplineId
     * @return ClassFaults[]
     */
    public function getFaultsByDiscipline($disciplineId)
    {
        /* @var $class Classes */
        /* @var $discipline EdcensoDiscipline */
        $faults = [];
        foreach ($this->classFaults as $fault) {
            $class = $fault->classFk;
            $discipline = $class->disciplineFk;
            if ($discipline->id == $disciplineId && $class->given_class == 1) {
                array_push($faults, $fault);
            }
        }
        return $faults;
    }

    public function getFaultsByExam($exam){
        /* @var $schoolConfiguration SchoolConfiguration */
        $schoolConfiguration = SchoolConfiguration::model()->findByAttributes(['school_inep_id_fk' => yii::app()->user->school]);
        $faults = [];
        switch ($exam) {
            case 1:
                $initial = new DateTime("01/01/" . yii::app()->user->year);
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
            $date = new DateTime($fault->classFk->day."-".$fault->classFk->month."-".yii::app()->user->year);
            if ($date > $initial && $date <= $final && $fault->classFk->given_class == 1) {
                array_push($faults,$fault);
            }
        }
        return $faults;
    }
}