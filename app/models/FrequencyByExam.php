<?php

/**
 * This is the model class for table "frequency_by_exam".
 *
 * The followings are the available columns in table 'frequency_by_exam':
 * @property integer $id
 * @property integer $enrollment_fk
 * @property integer $classroom_fk
 * @property integer $exam
 * @property integer $school_days
 * @property integer $workload
 * @property integer $absences
 *
 * The followings are the available model relations:
 * @property Classroom $classroomFk
 * @property StudentEnrollment $enrollmentFk
 */
class FrequencyByExam extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frequency_by_exam';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('enrollment_fk, classroom_fk, exam, school_days, workload, absences', 'required'),
			array('enrollment_fk, classroom_fk, exam, school_days, workload, absences', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, enrollment_fk, classroom_fk, exam, school_days, workload, absences', 'safe', 'on'=>'search'),
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
			'classroomFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_fk'),
			'enrollmentFk' => array(self::BELONGS_TO, 'StudentEnrollment', 'enrollment_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'enrollment_fk' => 'Enrollment Fk',
			'classroom_fk' => 'Classroom Fk',
			'exam' => 'Exam',
			'school_days' => 'School Days',
			'workload' => 'Workload',
			'absences' => 'Absences',
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
		$criteria->compare('enrollment_fk',$this->enrollment_fk);
		$criteria->compare('classroom_fk',$this->classroom_fk);
		$criteria->compare('exam',$this->exam);
		$criteria->compare('school_days',$this->school_days);
		$criteria->compare('workload',$this->workload);
		$criteria->compare('absences',$this->absences);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrequencyByExam the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
