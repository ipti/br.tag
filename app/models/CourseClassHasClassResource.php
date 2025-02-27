<?php

/**
 * This is the model class for table "course_class_has_class_resource".
 *
 * The followings are the available columns in table 'course_class_has_class_resource':
 * @property integer $id
 * @property integer $course_class_fk
 * @property integer $course_class_resource_fk
 * @property string $amount
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property CourseClassResources $courseClassResourceFk
 * @property CourseClass $courseClassFk
 */
class CourseClassHasClassResource extends TagModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'course_class_has_class_resource';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('course_class_fk, course_class_resource_fk', 'required'),
			array('course_class_fk, course_class_resource_fk', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>10),
			array('fkid', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, course_class_fk, course_class_resource_fk, amount, fkid', 'safe', 'on'=>'search'),
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
			'courseClassResourceFk' => array(self::BELONGS_TO, 'CourseClassResources', 'course_class_resource_fk'),
			'courseClassFk' => array(self::BELONGS_TO, 'CourseClass', 'course_class_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'course_class_fk' => 'Course Class Fk',
			'course_class_resource_fk' => 'Course Class Resource Fk',
			'amount' => 'Amount',
			'fkid' => 'Fkid',
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
		$criteria->compare('course_class_fk',$this->course_class_fk);
		$criteria->compare('course_class_resource_fk',$this->course_class_resource_fk);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('fkid',$this->fkid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CourseClassHasClassResource the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
