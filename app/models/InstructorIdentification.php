<?php

/**
 * This is the model class for table "instructor_identification".
 *
 * The followings are the available columns in table 'instructor_identification':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $inep_id
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $nis
 * @property string $birthday_date
 * @property integer $sex
 * @property integer $color_race
 * @property string $mother_name
 * @property integer $nationality
 * @property integer $edcenso_nation_fk
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 * @property integer $deficiency
 * @property integer $deficiency_type_blindness
 * @property integer $deficiency_type_low_vision
 * @property integer $deficiency_type_deafness
 * @property integer $deficiency_type_disability_hearing
 * @property integer $deficiency_type_deafblindness
 * @property integer $deficiency_type_phisical_disability
 * @property integer $deficiency_type_intelectual_disability
 * @property integer $deficiency_type_multiple_disabilities
 *
 * The followings are the available model relations:
 * @property EdcensoNation $edcensoNationFk
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 */
class InstructorIdentification extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InstructorIdentification the static model class
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
		return 'instructor_identification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk, name, email, nis, birthday_date, sex, color_race, nationality, edcenso_nation_fk, deficiency', 'required'),
			array('sex, color_race, nationality, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities', 'numerical', 'integerOnly'=>true),
			array('register_type', 'length', 'max'=>2),
			array('school_inep_id_fk', 'length', 'max'=>8),
			array('inep_id', 'length', 'max'=>12),
			array('name, email, mother_name', 'length', 'max'=>100),
			array('nis', 'length', 'max'=>11),
			array('birthday_date', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('register_type, school_inep_id_fk, inep_id, id, name, email, nis, birthday_date, sex, color_race, mother_name, nationality, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities', 'safe', 'on'=>'search'),
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
			'edcensoNationFk' => array(self::BELONGS_TO, 'EdcensoNation', 'edcenso_nation_fk'),
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
			'name' => Yii::t('default', 'Name'),
			'email' => Yii::t('default', 'Email'),
			'nis' => Yii::t('default', 'Nis'),
			'birthday_date' => Yii::t('default', 'Birthday Date'),
			'sex' => Yii::t('default', 'Sex'),
			'color_race' => Yii::t('default', 'Color Race'),
			'mother_name' => Yii::t('default', 'Mother Name'),
			'nationality' => Yii::t('default', 'Nationality'),
			'edcenso_nation_fk' => Yii::t('default', 'Edcenso Nation Fk'),
			'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
			'edcenso_city_fk' => Yii::t('default', 'Edcenso City Fk'),
			'deficiency' => Yii::t('default', 'Deficiency'),
			'deficiency_type_blindness' => Yii::t('default', 'Deficiency Type Blindness'),
			'deficiency_type_low_vision' => Yii::t('default', 'Deficiency Type Low Vision'),
			'deficiency_type_deafness' => Yii::t('default', 'Deficiency Type Deafness'),
			'deficiency_type_disability_hearing' => Yii::t('default', 'Deficiency Type Disability Hearing'),
			'deficiency_type_deafblindness' => Yii::t('default', 'Deficiency Type Deafblindness'),
			'deficiency_type_phisical_disability' => Yii::t('default', 'Deficiency Type Phisical Disability'),
			'deficiency_type_intelectual_disability' => Yii::t('default', 'Deficiency Type Intelectual Disability'),
			'deficiency_type_multiple_disabilities' => Yii::t('default', 'Deficiency Type Multiple Disabilities'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nis',$this->nis,true);
		$criteria->compare('birthday_date',$this->birthday_date,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('color_race',$this->color_race);
		$criteria->compare('mother_name',$this->mother_name,true);
		$criteria->compare('nationality',$this->nationality);
		$criteria->compare('edcenso_nation_fk',$this->edcenso_nation_fk);
		$criteria->compare('edcenso_uf_fk',$this->edcenso_uf_fk);
		$criteria->compare('edcenso_city_fk',$this->edcenso_city_fk);
		$criteria->compare('deficiency',$this->deficiency);
		$criteria->compare('deficiency_type_blindness',$this->deficiency_type_blindness);
		$criteria->compare('deficiency_type_low_vision',$this->deficiency_type_low_vision);
		$criteria->compare('deficiency_type_deafness',$this->deficiency_type_deafness);
		$criteria->compare('deficiency_type_disability_hearing',$this->deficiency_type_disability_hearing);
		$criteria->compare('deficiency_type_deafblindness',$this->deficiency_type_deafblindness);
		$criteria->compare('deficiency_type_phisical_disability',$this->deficiency_type_phisical_disability);
		$criteria->compare('deficiency_type_intelectual_disability',$this->deficiency_type_intelectual_disability);
		$criteria->compare('deficiency_type_multiple_disabilities',$this->deficiency_type_multiple_disabilities);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}