<?php

/**
 * This is the model class for table "class_faults".
 *
 * The followings are the available columns in table 'class_faults':
 * @property integer $id
 * @property integer $class_fk
 * @property integer $student_fk
 * @property integer $schedule
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property Classes $classFk
 * @property StudentEnrollment $studentFk
 */
class ClassFaults extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClassFaults the static model class
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
		return 'class_faults';
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
			array('class_fk, student_fk, schedule', 'required'),
			array('class_fk, student_fk, schedule', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, class_fk, student_fk, schedule', 'safe', 'on'=>'search'),
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
			'classFk' => array(self::BELONGS_TO, 'Classes', 'class_fk'),
			'studentFk' => array(self::BELONGS_TO, 'StudentEnrollment', 'student_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'class_fk' => Yii::t('default', 'Class Fk'),
			'student_fk' => Yii::t('default', 'Student Fk'),
			'schedule' => Yii::t('default', 'Schedule'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('class_fk',$this->class_fk);
		$criteria->compare('student_fk',$this->student_fk);
		$criteria->compare('schedule',$this->schedule);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}