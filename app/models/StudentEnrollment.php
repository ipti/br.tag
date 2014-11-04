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
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 * @property Classroom $classroomFk
 * @property SchoolIdentification $schoolInepIdFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 */
class StudentEnrollment extends CActiveRecord {

    public $school_year;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return StudentEnrollment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'student_enrollment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_inep_id_fk, student_fk, classroom_fk,another_scholarization_place, public_transport', 'required'),
            array('student_fk, classroom_fk, unified_class, edcenso_stage_vs_modality_fk, another_scholarization_place, public_transport, transport_responsable_government, vehicle_type_van, vehicle_type_microbus, vehicle_type_bus, vehicle_type_bike, vehicle_type_animal_vehicle, vehicle_type_other_vehicle, vehicle_type_waterway_boat_5, vehicle_type_waterway_boat_5_15, vehicle_type_waterway_boat_15_35, vehicle_type_waterway_boat_35, vehicle_type_metro_or_train, student_entry_form', 'numerical', 'integerOnly' => true),
            array('register_type', 'length', 'max' => 2),
            array('school_inep_id_fk', 'length', 'max' => 8),
            array('student_inep_id, classroom_inep_id, enrollment_id', 'length', 'max' => 12),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('school_year,studentFk.name,classroomFk.name,register_type, school_inep_id_fk, student_inep_id, student_fk, classroom_inep_id, classroom_fk, enrollment_id, unified_class, edcenso_stage_vs_modality_fk, another_scholarization_place, public_transport, transport_responsable_government, vehicle_type_van, vehicle_type_microbus, vehicle_type_bus, vehicle_type_bike, vehicle_type_animal_vehicle, vehicle_type_other_vehicle, vehicle_type_waterway_boat_5, vehicle_type_waterway_boat_5_15, vehicle_type_waterway_boat_15_35, vehicle_type_waterway_boat_35, vehicle_type_metro_or_train, student_entry_form, id, create_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'studentFk' => array(self::BELONGS_TO, 'StudentIdentification', 'student_fk'),
            'classroomFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_fk'),
            'schoolInepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'),
            'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
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
        	'create_date' => Yii::t('default', 'Create Time')
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->with = array('studentFk', 'classroomFk');
        $criteria->together = true;
        
//                $criteria->compare('register_type',$this->register_type,true);
//                $criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);
//                $criteria->compare('student_inep_id',$this->student_inep_id,true);
//                //$criteria->compare('student_fk',$this->student_fk, true);
//                $criteria->compare('classroom_inep_id',$this->classroom_inep_id,true);
//                $criteria->compare('classroom_fk',$this->classroom_fk);
        $criteria->compare('enrollment_id',$this->enrollment_id,true);
//                $criteria->compare('unified_class',$this->unified_class);
//                $criteria->compare('edcenso_stage_vs_modality_fk',$this->edcenso_stage_vs_modality_fk);
//                $criteria->compare('another_scholarization_place',$this->another_scholarization_place);
//                $criteria->compare('public_transport',$this->public_transport);
//                $criteria->compare('transport_responsable_government',$this->transport_responsable_government);
//                $criteria->compare('vehicle_type_van',$this->vehicle_type_van);
//                $criteria->compare('vehicle_type_microbus',$this->vehicle_type_microbus);
//                $criteria->compare('vehicle_type_bus',$this->vehicle_type_bus);
//                $criteria->compare('vehicle_type_bike',$this->vehicle_type_bike);
//                $criteria->compare('vehicle_type_animal_vehicle',$this->vehicle_type_animal_vehicle);
//                $criteria->compare('vehicle_type_other_vehicle',$this->vehicle_type_other_vehicle);
//                $criteria->compare('vehicle_type_waterway_boat_5',$this->vehicle_type_waterway_boat_5);
//                $criteria->compare('vehicle_type_waterway_boat_5_15',$this->vehicle_type_waterway_boat_5_15);
//                $criteria->compare('vehicle_type_waterway_boat_15_35',$this->vehicle_type_waterway_boat_15_35);
//                $criteria->compare('vehicle_type_waterway_boat_35',$this->vehicle_type_waterway_boat_35);
//                $criteria->compare('vehicle_type_metro_or_train',$this->vehicle_type_metro_or_train);
//                $criteria->compare('student_entry_form',$this->student_entry_form);
        $criteria->compare('id', $this->id);
        $criteria->compare( 'classroomFk.school_year', Yii::app()->user->year );
        $school = Yii::app()->user->school;
        $criteria->compare('t.school_inep_id_fk', $school);
        $criteria->addCondition('studentFk.name like "%' . $this->student_fk . '%"');
        $criteria->addCondition('classroomFk.name like "%' . $this->classroom_fk . '%"');

//                $criteria->compare('StudentIdentification.name',$this->studentFk->name, true);


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

}