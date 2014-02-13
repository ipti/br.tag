<?php

/**
 * This is the model class for table "class_board".
 *
 * The followings are the available columns in table 'class_board':
 * @property integer $id
 * @property integer $discipline_fk
 * @property integer $classroom_fk
 * @property integer $week_day_monday
 * @property integer $week_day_tuesday
 * @property integer $week_day_wednesday
 * @property integer $week_day_thursday
 * @property integer $week_day_friday
 * @property integer $week_day_saturday
 * @property integer $week_day_sunday
 * @property integer $estimated_classes
 * @property integer $given_classes
 * @property integer $replaced_classes
 *
 * The followings are the available model relations:
 * @property Classroom $classroomFk
 * @property EdcensoDiscipline $disciplineFk
 */
class ClassBoard extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_board';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('discipline_fk, classroom_fk', 'required'),
			array('discipline_fk, classroom_fk, week_day_monday, week_day_tuesday, week_day_wednesday, week_day_thursday, week_day_friday, week_day_saturday, week_day_sunday, estimated_classes, given_classes, replaced_classes', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, discipline_fk, classroom_fk, week_day_monday, week_day_tuesday, week_day_wednesday, week_day_thursday, week_day_friday, week_day_saturday, week_day_sunday, estimated_classes, given_classes, replaced_classes', 'safe', 'on'=>'search'),
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
			'disciplineFk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'discipline_fk' => Yii::t('default', 'Discipline Fk'),
			'classroom_fk' => Yii::t('default', 'Classroom Fk'),
			'week_day_monday' => Yii::t('default', 'Week Day Monday'),
			'week_day_tuesday' => Yii::t('default', 'Week Day Tuesday'),
			'week_day_wednesday' => Yii::t('default', 'Week Day Wednesday'),
			'week_day_thursday' => Yii::t('default', 'Week Day Thursday'),
			'week_day_friday' => Yii::t('default', 'Week Day Friday'),
			'week_day_saturday' => Yii::t('default', 'Week Day Saturday'),
			'week_day_sunday' => Yii::t('default', 'Week Day Sunday'),
			'estimated_classes' => Yii::t('default', 'Estimated Classes'),
			'given_classes' => Yii::t('default', 'Given Classes'),
			'replaced_classes' => Yii::t('default', 'Replaced Classes'),
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
		$criteria->compare('discipline_fk',$this->discipline_fk);
		$criteria->compare('classroom_fk',$this->classroom_fk);
		$criteria->compare('week_day_monday',$this->week_day_monday);
		$criteria->compare('week_day_tuesday',$this->week_day_tuesday);
		$criteria->compare('week_day_wednesday',$this->week_day_wednesday);
		$criteria->compare('week_day_thursday',$this->week_day_thursday);
		$criteria->compare('week_day_friday',$this->week_day_friday);
		$criteria->compare('week_day_saturday',$this->week_day_saturday);
		$criteria->compare('week_day_sunday',$this->week_day_sunday);
		$criteria->compare('estimated_classes',$this->estimated_classes);
		$criteria->compare('given_classes',$this->given_classes);
		$criteria->compare('replaced_classes',$this->replaced_classes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassBoard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
