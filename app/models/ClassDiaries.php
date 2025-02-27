<?php

/**
 * This is the model class for table "class_diaries".
 *
 * The followings are the available columns in table 'class_diaries':
 * @property integer $id
 * @property integer $schedule_fk
 * @property integer $student_fk
 * @property string $diary
 *
 * The followings are the available model relations:
 * @property Schedule $scheduleFk
 * @property StudentIdentification $studentFk
 */
class ClassDiaries extends TagModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_diaries';
	}



	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('schedule_fk, student_fk, diary', 'required'),
			array('schedule_fk, student_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, schedule_fk, student_fk, diary', 'safe', 'on'=>'search'),
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
			'studentFk' => array(self::BELONGS_TO, 'StudentIdentification', 'student_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'schedule_fk' => 'Schedule Fk',
			'student_fk' => 'Student Fk',
			'diary' => 'Diary',
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
		$criteria->compare('schedule_fk',$this->schedule_fk);
		$criteria->compare('student_fk',$this->student_fk);
		$criteria->compare('diary',$this->diary,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassDiaries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
