<?php

/**
 * This is the model class for table "instructor_teaching_data".
 *
 * The followings are the available columns in table 'instructor_teaching_data':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $instructor_inep_id
 * @property integer $instructor_fk
 * @property string $classroom_inep_id
 * @property integer $classroom_id_fk
 * @property integer $role
 * @property integer $contract_type
 * @property integer $discipline_1_fk
 * @property integer $discipline_2_fk
 * @property integer $discipline_3_fk
 * @property integer $discipline_4_fk
 * @property integer $discipline_5_fk
 * @property integer $discipline_6_fk
 * @property integer $discipline_7_fk
 * @property integer $discipline_8_fk
 * @property integer $discipline_9_fk
 * @property integer $discipline_10_fk
 * @property integer $discipline_11_fk
 * @property integer $discipline_12_fk
 * @property integer $discipline_13_fk
 * @property integer $id
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property InstructorIdentification $instructorFk
 * @property Classroom $classroomIdFk
 * @property EdcensoDiscipline $discipline1Fk
 * @property SchoolIdentification $schoolInepIdFk
 * @property EdcensoDiscipline $discipline10Fk
 * @property EdcensoDiscipline $discipline11Fk
 * @property EdcensoDiscipline $discipline12Fk
 * @property EdcensoDiscipline $discipline13Fk
 * @property EdcensoDiscipline $discipline2Fk
 * @property EdcensoDiscipline $discipline3Fk
 * @property EdcensoDiscipline $discipline4Fk
 * @property EdcensoDiscipline $discipline5Fk
 * @property EdcensoDiscipline $discipline6Fk
 * @property EdcensoDiscipline $discipline7Fk
 * @property EdcensoDiscipline $discipline8Fk
 * @property EdcensoDiscipline $discipline9Fk
 */
class InstructorTeachingData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InstructorTeachingData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'instructor_teaching_data';
	}

        public function behaviors() {
            return [
                'afterSave'=>[
                    'class'=>'application.behaviors.CAfterSaveBehavior',
                    'schoolInepId' => Yii::app()->user->school,
                ],
            ];
        }
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk, instructor_fk, classroom_id_fk, role', 'required'),
			array('instructor_fk, classroom_id_fk, role, contract_type, discipline_1_fk, discipline_2_fk, discipline_3_fk, discipline_4_fk, discipline_5_fk, discipline_6_fk, discipline_7_fk, discipline_8_fk, discipline_9_fk, discipline_10_fk, discipline_11_fk, discipline_12_fk, discipline_13_fk', 'numerical', 'integerOnly'=>true),
			array('register_type', 'length', 'max'=>2),
			array('school_inep_id_fk, classroom_inep_id', 'length', 'max'=>8),
			array('instructor_inep_id', 'length', 'max'=>12),
			array('fkid', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('register_type, school_inep_id_fk, instructor_inep_id, instructor_fk, classroom_inep_id, classroom_id_fk, role, contract_type, discipline_1_fk, discipline_2_fk, discipline_3_fk, discipline_4_fk, discipline_5_fk, discipline_6_fk, discipline_7_fk, discipline_8_fk, discipline_9_fk, discipline_10_fk, discipline_11_fk, discipline_12_fk, discipline_13_fk, id, fkid', 'safe', 'on'=>'search'),
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
			'instructorFk' => array(self::BELONGS_TO, 'InstructorIdentification', 'instructor_fk'),
			'classroomIdFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_id_fk'),
			'discipline1Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_1_fk'),
			'schoolInepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'),
			'discipline10Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_10_fk'),
			'discipline11Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_11_fk'),
			'discipline12Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_12_fk'),
			'discipline13Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_13_fk'),
			'discipline2Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_2_fk'),
			'discipline3Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_3_fk'),
			'discipline4Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_4_fk'),
			'discipline5Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_5_fk'),
			'discipline6Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_6_fk'),
			'discipline7Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_7_fk'),
			'discipline8Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_8_fk'),
			'discipline9Fk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_9_fk'),
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
			'instructor_inep_id' => Yii::t('default', 'Instructor Inep'),
			'instructor_fk' => Yii::t('default', 'Instructor Fk'),
			'classroom_inep_id' => Yii::t('default', 'Classroom Inep'),
			'classroom_id_fk' => Yii::t('default', 'Classroom Id Fk'),
			'role' => Yii::t('default', 'Role'),
			'contract_type' => Yii::t('default', 'Contract Type'),
			'discipline_1_fk' => Yii::t('default', 'Discipline 1 Fk'),
			'discipline_2_fk' => Yii::t('default', 'Discipline 2 Fk'),
			'discipline_3_fk' => Yii::t('default', 'Discipline 3 Fk'),
			'discipline_4_fk' => Yii::t('default', 'Discipline 4 Fk'),
			'discipline_5_fk' => Yii::t('default', 'Discipline 5 Fk'),
			'discipline_6_fk' => Yii::t('default', 'Discipline 6 Fk'),
			'discipline_7_fk' => Yii::t('default', 'Discipline 7 Fk'),
			'discipline_8_fk' => Yii::t('default', 'Discipline 8 Fk'),
			'discipline_9_fk' => Yii::t('default', 'Discipline 9 Fk'),
			'discipline_10_fk' => Yii::t('default', 'Discipline 10 Fk'),
			'discipline_11_fk' => Yii::t('default', 'Discipline 11 Fk'),
			'discipline_12_fk' => Yii::t('default', 'Discipline 12 Fk'),
			'discipline_13_fk' => Yii::t('default', 'Discipline 13 Fk'),
			'id' => Yii::t('default', 'ID'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('register_type',$this->register_type,true);
		$criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);
		$criteria->compare('instructor_inep_id',$this->instructor_inep_id,true);
		$criteria->compare('instructor_fk',$this->instructor_fk);
		$criteria->compare('classroom_inep_id',$this->classroom_inep_id,true);
		$criteria->compare('classroom_id_fk',$this->classroom_id_fk);
		$criteria->compare('role',$this->role);
		$criteria->compare('contract_type',$this->contract_type);
		$criteria->compare('discipline_1_fk',$this->discipline_1_fk);
		$criteria->compare('discipline_2_fk',$this->discipline_2_fk);
		$criteria->compare('discipline_3_fk',$this->discipline_3_fk);
		$criteria->compare('discipline_4_fk',$this->discipline_4_fk);
		$criteria->compare('discipline_5_fk',$this->discipline_5_fk);
		$criteria->compare('discipline_6_fk',$this->discipline_6_fk);
		$criteria->compare('discipline_7_fk',$this->discipline_7_fk);
		$criteria->compare('discipline_8_fk',$this->discipline_8_fk);
		$criteria->compare('discipline_9_fk',$this->discipline_9_fk);
		$criteria->compare('discipline_10_fk',$this->discipline_10_fk);
		$criteria->compare('discipline_11_fk',$this->discipline_11_fk);
		$criteria->compare('discipline_12_fk',$this->discipline_12_fk);
		$criteria->compare('discipline_13_fk',$this->discipline_13_fk);
		$criteria->compare('id',$this->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}