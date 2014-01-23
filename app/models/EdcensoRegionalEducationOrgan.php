<?php

/**
 * This is the model class for table "edcenso_regional_education_organ".
 *
 * The followings are the available columns in table 'edcenso_regional_education_organ':
 * @property integer $id
 * @property integer $edcenso_city_fk
 * @property string $code
 * @property string $name
 * @property integer $edcenso_uf_fk
 *
 * The followings are the available model relations:
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 */
class EdcensoRegionalEducationOrgan extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EdcensoRegionalEducationOrgan the static model class
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
		return 'edcenso_regional_education_organ';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('edcenso_city_fk, code, name, edcenso_uf_fk', 'required'),
			array('edcenso_city_fk, edcenso_uf_fk', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>5),
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, edcenso_city_fk, code, name, edcenso_uf_fk', 'safe', 'on'=>'search'),
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
			'edcensoUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'),
			'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
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
			'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('edcenso_uf_fk',$this->edcenso_uf_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}