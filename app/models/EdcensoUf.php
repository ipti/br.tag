<?php

/**
 * This is the model class for table "edcenso_uf".
 *
 * The followings are the available columns in table 'edcenso_uf':
 * @property integer $id
 * @property string $acronym
 * @property string $name
 *
 * The followings are the available model relations:
 * @property EdcensoCity[] $edcensoCities
 * @property InstructorDocumentsAndAddress[] $instructorDocumentsAndAddresses
 * @property InstructorIdentification[] $instructorIdentifications
 * @property SchoolIdentification[] $schoolIdentifications
 * @property StudentDocumentsAndAddress[] $studentDocumentsAndAddresses
 * @property StudentDocumentsAndAddress[] $studentDocumentsAndAddresses1
 * @property StudentDocumentsAndAddress[] $studentDocumentsAndAddresses2
 * @property StudentIdentification[] $studentIdentifications
 */
class EdcensoUf extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EdcensoUf the static model class
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
		return 'edcenso_uf';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, acronym, name', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('acronym', 'length', 'max'=>2),
			array('name', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, acronym, name', 'safe', 'on'=>'search'),
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
			'edcensoCities' => array(self::HAS_MANY, 'EdcensoCity', 'edcenso_uf_fk'),
			'instructorDocumentsAndAddresses' => array(self::HAS_MANY, 'InstructorDocumentsAndAddress', 'edcenso_uf_fk'),
			'instructorIdentifications' => array(self::HAS_MANY, 'InstructorIdentification', 'edcenso_uf_fk'),
			'schoolIdentifications' => array(self::HAS_MANY, 'SchoolIdentification', 'edcenso_uf_fk'),
			'studentDocumentsAndAddresses' => array(self::HAS_MANY, 'StudentDocumentsAndAddress', 'rg_number_edcenso_uf_fk'),
			'studentDocumentsAndAddresses1' => array(self::HAS_MANY, 'StudentDocumentsAndAddress', 'edcenso_uf_fk'),
			'studentDocumentsAndAddresses2' => array(self::HAS_MANY, 'StudentDocumentsAndAddress', 'notary_office_uf_fk'),
			'studentIdentifications' => array(self::HAS_MANY, 'StudentIdentification', 'edcenso_uf_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'acronym' => Yii::t('default', 'Acronym'),
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
		$criteria->compare('acronym',$this->acronym,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}