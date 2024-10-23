<?php

/**
 * This is the model class for table "substitute_instructor".
 *
 * The followings are the available columns in table 'substitute_instructor':
 * @property integer $id
 * @property integer $instructor_fk
 * @property integer $teaching_data_fk
 *
 * The followings are the available model relations:
 * @property Schedule[] $schedules
 * @property InstructorIdentification $instructorFk
 * @property InstructorTeachingData $teachingDataFk
 */
class SubstituteInstructor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'substitute_instructor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, instructor_fk, teaching_data_fk', 'required'),
			array('id, instructor_fk, teaching_data_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, instructor_fk, teaching_data_fk', 'safe', 'on'=>'search'),
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
			'schedules' => array(self::HAS_MANY, 'Schedule', 'substitute_instructor_fk'),
			'instructorFk' => array(self::BELONGS_TO, 'InstructorIdentification', 'instructor_fk'),
			'teachingDataFk' => array(self::BELONGS_TO, 'InstructorTeachingData', 'teaching_data_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'instructor_fk' => 'Instructor Fk',
			'teaching_data_fk' => 'Teaching Data Fk',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('instructor_fk',$this->instructor_fk);
		$criteria->compare('teaching_data_fk',$this->teaching_data_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SubstituteInstructor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
