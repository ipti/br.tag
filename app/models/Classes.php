<?php

/**
 * This is the model class for table "class".
 *
 * The followings are the available columns in table 'class':
 * @property integer $id
 * @property integer $discipline_fk
 * @property integer $classroom_fk
 * @property integer $day
 * @property integer $month
 * @property string $classtype
 * @property integer $given_class
 * @property string $schedule
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property EdcensoDiscipline $disciplineFk
 * @property Classroom $classroomFk
 * @property ClassHasContent[] $ClassContents
 * @property ClassFaults[] $classFaults
 */
class Classes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Classes the static model class
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
		return 'class';
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
			array('classroom_fk, day, month', 'required'),
			array('discipline_fk, classroom_fk, day, month, given_class', 'numerical', 'integerOnly'=>true),
			array('classtype', 'length', 'max'=>1),
			array('schedule', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, discipline_fk, classroom_fk, day, month, classtype, given_class, schedule', 'safe', 'on'=>'search'),
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
			'disciplineFk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'),
			'classroomFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_fk'),
			'classFaults' => array(self::HAS_MANY, 'ClassFaults', 'class_fk'),
                        'classContents' => array(self::HAS_MANY, 'ClassHasContent', 'class_fk'),

                    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'discipline_fk' => Yii::t('default', 'Discipline Fk'),
			'classroom_fk' => Yii::t('default', 'Classroom Fk'),
			'day' => Yii::t('default', 'Day'),
			'month' => Yii::t('default', 'Month'),
			'classtype' => Yii::t('default', 'Classtype'),
			'given_class' => Yii::t('default', 'Given Class'),
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
		$criteria->compare('discipline_fk',$this->discipline_fk);
		$criteria->compare('classroom_fk',$this->classroom_fk);
		$criteria->compare('day',$this->day);
		$criteria->compare('month',$this->month);
		$criteria->compare('classtype',$this->classtype,true);
		$criteria->compare('given_class',$this->given_class);
		$criteria->compare('schedule',$this->schedule,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}