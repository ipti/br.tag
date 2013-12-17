<?php

/**
 * This is the model class for table "edcenso_discipline".
 *
 * The followings are the available columns in table 'edcenso_discipline':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property InstructorTeachingData[] $instructorTeachingDatas
 * @property InstructorTeachingData[] $instructorTeachingDatas1
 * @property InstructorTeachingData[] $instructorTeachingDatas2
 * @property InstructorTeachingData[] $instructorTeachingDatas3
 * @property InstructorTeachingData[] $instructorTeachingDatas4
 * @property InstructorTeachingData[] $instructorTeachingDatas5
 * @property InstructorTeachingData[] $instructorTeachingDatas6
 * @property InstructorTeachingData[] $instructorTeachingDatas7
 * @property InstructorTeachingData[] $instructorTeachingDatas8
 * @property InstructorTeachingData[] $instructorTeachingDatas9
 * @property InstructorTeachingData[] $instructorTeachingDatas10
 * @property InstructorTeachingData[] $instructorTeachingDatas11
 */
class EdcensoDiscipline extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EdcensoDiscipline the static model class
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
		return 'edcenso_discipline';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'instructorTeachingDatas' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_10_fk'),
			'instructorTeachingDatas1' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_11_fk'),
			'instructorTeachingDatas2' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_12_fk'),
			'instructorTeachingDatas3' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_13_fk'),
			'instructorTeachingDatas4' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_2_fk'),
			'instructorTeachingDatas5' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_3_fk'),
			'instructorTeachingDatas6' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_4_fk'),
			'instructorTeachingDatas7' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_5_fk'),
			'instructorTeachingDatas8' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_6_fk'),
			'instructorTeachingDatas9' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_7_fk'),
			'instructorTeachingDatas10' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_8_fk'),
			'instructorTeachingDatas11' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_9_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'name' => Yii::t('default', 'Name'),
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}