<?php

/**
 * This is the model class for table "edcenso_course_of_higher_education".
 *
 * The followings are the available columns in table 'edcenso_course_of_higher_education':
 * @property integer $cod
 * @property string $area
 * @property string $id
 * @property string $name
 * @property string $degree
 *
 * The followings are the available model relations:
 * @property InstructorVariableData[] $instructorVariableDatas
 * @property InstructorVariableData[] $instructorVariableDatas1
 * @property InstructorVariableData[] $instructorVariableDatas2
 */
class EdcensoCourseOfHigherEducation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EdcensoCourseOfHigherEducation the static model class
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
		return 'edcenso_course_of_higher_education';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod, area, id, name, degree', 'required'),
			array('cod', 'numerical', 'integerOnly'=>true),
			array('area', 'length', 'max'=>45),
			array('id', 'length', 'max'=>6),
			array('name', 'length', 'max'=>100),
			array('degree', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cod, area, id, name, degree', 'safe', 'on'=>'search'),
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
			'instructorVariableDatas' => array(self::HAS_MANY, 'InstructorVariableData', 'high_education_course_code_1_fk'),
			'instructorVariableDatas1' => array(self::HAS_MANY, 'InstructorVariableData', 'high_education_course_code_2_fk'),
			'instructorVariableDatas2' => array(self::HAS_MANY, 'InstructorVariableData', 'high_education_course_code_3_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cod' => Yii::t('default', 'Cod'),
			'area' => Yii::t('default', 'Area'),
			'id' => Yii::t('default', 'ID'),
			'name' => Yii::t('default', 'Name'),
			'degree' => Yii::t('default', 'Degree'),
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

		$criteria->compare('cod',$this->cod);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('degree',$this->degree,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}