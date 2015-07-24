<?php

/**
 * This is the model class for table "classroom_has_course_plan".
 *
 * The followings are the available columns in table 'classroom_has_course_plan':
 * @property integer $id
 * @property integer $classroom_fk
 * @property integer $course_plan_fk
 *
 * The followings are the available model relations:
 * @property Classroom $classroomFk
 * @property CoursePlan $coursePlanFk
 */
class ClassroomHasCoursePlan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'classroom_has_course_plan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classroom_fk, course_plan_fk', 'required'),
			array('classroom_fk, course_plan_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, classroom_fk, course_plan_fk', 'safe', 'on'=>'search'),
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
			'coursePlanFk' => array(self::BELONGS_TO, 'CoursePlan', 'course_plan_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'classroom_fk' => 'Classroom Fk',
			'course_plan_fk' => 'Course Plan Fk',
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
		$criteria->compare('classroom_fk',$this->classroom_fk);
		$criteria->compare('course_plan_fk',$this->course_plan_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassroomHasCoursePlan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
