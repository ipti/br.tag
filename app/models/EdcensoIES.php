<?php

/**
 * This is the model class for table "edcenso_ies".
 *
 * The followings are the available columns in table 'edcenso_ies':
 * @property integer $id
 * @property string  $name
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 * @property integer $administrative_dependency_code
 * @property string  $administrative_dependency_name
 * @property string  $institution_type
 * @property string  $working_status
 *
 * The followings are the available model relations:
 *
 */
class EdcensoIES extends TagModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EdcensoIES the static model class
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
		return 'edcenso_ies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, code, name', 'required'),
			array('id, code', 'numerical', 'integerOnly'=>true),
			//array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
//	public function relations()
//	{
//		// NOTE: you may need to adjust the relation name and the related
//		// class name for the relations automatically generated below.
//		return array(
//			'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
//			'schoolIdentifications' => array(self::HAS_MANY, 'SchoolIdentification', 'edcenso_district_fk'),
//		);
//	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
			//'code' => Yii::t('default', 'Code'),
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
//		$criteria->compare('edcenso_city_fk',$this->edcenso_city_fk);
//		$criteria->compare('code',$this->code);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
