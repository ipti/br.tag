<?php

/**
 * This is the model class for table "professional".
 *
 * The followings are the available columns in table 'professional':
 * @property integer $id_professional
 * @property string $name
 * @property string $cpf_professional
 * @property integer $speciality_fk
 * @property string $inep_id_fk
 * @property integer $fundeb
 *
 * The followings are the available model relations:
 * @property Attendance[] $attendances
 * @property SchoolIdentification $inepIdFk
 * @property EdcensoProfessionalEducationCourse $specialityFk
 */
class Professional extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'professional';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, cpf_professional, speciality_fk, inep_id_fk, fundeb', 'required'),
			array('speciality_fk, fundeb', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			array('cpf_professional', 'length', 'max'=>14),
			array('inep_id_fk', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_professional, name, cpf_professional, speciality_fk, inep_id_fk, fundeb', 'safe', 'on'=>'search'),
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
			'attendances' => array(self::HAS_MANY, 'Attendance', 'professional_fk'),
			'inepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'inep_id_fk'),
			'specialityFk' => array(self::BELONGS_TO, 'EdcensoProfessionalEducationCourse', 'speciality_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_professional' => 'Id Professional',
			'name' => 'Nome',
			'cpf_professional' => 'CPF',
			'speciality_fk' => 'Especialidade',
			'inep_id_fk' => 'Inep Id Fk',
			'fundeb' => 'Fundeb',
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

		$criteria->compare('id_professional',$this->id_professional);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('cpf_professional',$this->cpf_professional,true);
		$criteria->compare('speciality_fk',$this->speciality_fk);
		$criteria->compare('inep_id_fk',$this->inep_id_fk,true);
		$criteria->compare('fundeb',$this->fundeb);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Professional the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
