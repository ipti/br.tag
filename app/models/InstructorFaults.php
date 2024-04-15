<?php

/**
 * This is the model class for table "class_faults".
 *
 * The followings are the available columns in table 'class_faults':
 * @property integer $id
 * @property integer $schedule_fk
 * @property integer $instructor_fk
 * @property string $justification
 *
 * The followings are the available model relations:
 * @property Schedule $scheduleFk
 * @property InstructorIdentification $instructor_fk
 */
class InstructorFaults extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InstructorFaults the static model class
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
		return 'instructor_faults';
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
			array('schedule_fk, instructor_fk', 'required'),
			array('schedule_fk, instructor_fk', 'numerical', 'integerOnly'=>true),
            array('justification', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, schedule_fk, instructor_fk', 'safe', 'on'=>'search'),
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
			'scheduleFk' => array(self::BELONGS_TO, 'Schedule', 'schedule_fk'),
			'instructorFk' => array(self::BELONGS_TO, 'InstructorTeachingData', 'instructor_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'schedule_fk' => Yii::t('default', 'Schedule Fk'),
			'instructor_fk' => Yii::t('default', 'Instructor Fk'),
            'justification' => Yii::t('default', "Justification")
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
		$criteria->compare('schedule_fk',$this->schedule_fk);
		$criteria->compare('instructor_fk',$this->instructor_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}