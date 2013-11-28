<?php

/**
 * This is the model class for table "edcenso_district".
 *
 * The followings are the available columns in table 'edcenso_district':
 * @property integer $id
 * @property integer $edcenso_city_fk
 * @property integer $code
 * @property string $name
 *
 * The followings are the available model relations:
 * @property EdcensoCity $edcensoCityFk
 * @property SchoolIdentification[] $schoolIdentifications
 */
class EdcensoDistrict extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EdcensoDistrict the static model class
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
		return 'edcenso_district';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('edcenso_city_fk, code, name', 'required'),
			array('edcenso_city_fk, code', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, edcenso_city_fk, code, name', 'safe', 'on'=>'search'),
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
			'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
			'schoolIdentifications' => array(self::HAS_MANY, 'SchoolIdentification', 'edcenso_district_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'edcenso_city_fk' => Yii::t('default', 'Edcenso City Fk'),
			'code' => Yii::t('default', 'Code'),
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
		$criteria->compare('edcenso_city_fk',$this->edcenso_city_fk);
		$criteria->compare('code',$this->code);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}