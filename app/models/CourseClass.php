<?php

/**
 * This is the model class for table "course_class".
 *
 * The followings are the available columns in table 'course_class':
 * @property integer $id
 * @property integer $order
 * @property string $objective
 * @property integer $course_plan_fk
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property CoursePlan $coursePlanFk
 * @property CourseClassHasClassResource[] $courseClassHasClassResources
 */
class CourseClass extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'course_class';
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
			array('order, objective, course_plan_fk', 'required'),
			array('order, course_plan_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order, objective, course_plan_fk', 'safe', 'on'=>'search'),
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
			'coursePlanFk' => array(self::BELONGS_TO, 'CoursePlan', 'course_plan_fk'),
			'courseClassHasClassResources' => array(self::HAS_MANY, 'CourseClassHasClassResource', 'course_class_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order' => 'Order',
			'objective' => 'Objective',
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
		$criteria->compare('order',$this->order);
		$criteria->compare('objective',$this->objective,true);
		$criteria->compare('course_plan_fk',$this->course_plan_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CourseClass the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
