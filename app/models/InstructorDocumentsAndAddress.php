<?php

/**
 * This is the model class for table "instructor_documents_and_address".
 *
 * The followings are the available columns in table 'instructor_documents_and_address':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $inep_id
 * @property integer $id
 * @property string $cpf
 * @property integer $area_of_residence
 * @property string $cep
 * @property string $address
 * @property string $address_number
 * @property string $complement
 * @property string $neighborhood
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolInepIdFk
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 */
class InstructorDocumentsAndAddress extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InstructorDocumentsAndAddress the static model class
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
		return 'instructor_documents_and_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk', 'required'),
			array('area_of_residence, edcenso_uf_fk, edcenso_city_fk', 'numerical', 'integerOnly'=>true),
			array('register_type', 'length', 'max'=>2),
			array('school_inep_id_fk, cep', 'length', 'max'=>8),
			array('inep_id', 'length', 'max'=>12),
			array('cpf', 'length', 'max'=>11),
			array('address', 'length', 'max'=>100),
			array('address_number', 'length', 'max'=>10),
			array('complement', 'length', 'max'=>20),
			array('neighborhood', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('register_type, school_inep_id_fk, inep_id, id, cpf, area_of_residence, cep, address, address_number, complement, neighborhood, edcenso_uf_fk, edcenso_city_fk', 'safe', 'on'=>'search'),
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
			'schoolInepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'),
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
			'register_type' => Yii::t('default', 'Register Type'),
			'school_inep_id_fk' => Yii::t('default', 'School Inep Id Fk'),
			'inep_id' => Yii::t('default', 'Inep'),
			'id' => Yii::t('default', 'ID'),
			'cpf' => Yii::t('default', 'Cpf'),
			'area_of_residence' => Yii::t('default', 'Area Of Residence'),
			'cep' => Yii::t('default', 'Cep'),
			'address' => Yii::t('default', 'Address'),
			'address_number' => Yii::t('default', 'Address Number'),
			'complement' => Yii::t('default', 'Complement'),
			'neighborhood' => Yii::t('default', 'Neighborhood'),
			'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
			'edcenso_city_fk' => Yii::t('default', 'Edcenso City Fk'),
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

		$criteria->compare('register_type',$this->register_type,true);
		$criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);
		$criteria->compare('inep_id',$this->inep_id,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('cpf',$this->cpf,true);
		$criteria->compare('area_of_residence',$this->area_of_residence);
		$criteria->compare('cep',$this->cep,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address_number',$this->address_number,true);
		$criteria->compare('complement',$this->complement,true);
		$criteria->compare('neighborhood',$this->neighborhood,true);
		$criteria->compare('edcenso_uf_fk',$this->edcenso_uf_fk);
		$criteria->compare('edcenso_city_fk',$this->edcenso_city_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}