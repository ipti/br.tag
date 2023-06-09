<?php

/**
 * This is the model class for table "manager_identification".
 *
 * The followings are the available columns in table 'manager_identification':
 * @property string $register_type
 * @property integer $id
 * @property string $school_inep_id_fk
 * @property string $inep_id
 * @property string $name
 * @property string $email
 * @property string $birthday_date
 * @property integer $sex
 * @property integer $color_race
 * @property integer $nationality
 * @property integer $role
 * @property integer $residence_zone
 * @property string $access_criterion
 * @property integer $contract_type
 * @property string $cpf
 * @property string $number_ato
 * @property integer $filiation
 * @property string $filiation_1
 * @property string $filiation_2
 * @property string $filiation_1_rg
 * @property string $filiation_1_cpf
 * @property integer $filiation_1_scholarity
 * @property string $filiation_1_job
 * @property string $filiation_2_rg
 * @property string $filiation_2_cpf
 * @property integer $filiation_2_scholarity
 * @property string $filiation_2_job
 * @property integer $edcenso_nation_fk
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 * @property integer $users_fk
 *
 * The followings are the available model relations:
 * @property EdcensoNation $edcensoNationFk
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 * @property Users $usersFk
 */
class ManagerIdentification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'manager_identification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk, name, birthday_date, sex, color_race, nationality, residence_zone, filiation, edcenso_nation_fk', 'required'),
			array('sex, color_race, nationality, role, residence_zone, contract_type, filiation, filiation_1_scholarity, filiation_2_scholarity, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, users_fk', 'numerical', 'integerOnly'=>true),
			array('register_type', 'length', 'max'=>2),
			array('school_inep_id_fk', 'length', 'max'=>8),
			array('inep_id', 'length', 'max'=>12),
			array('name, email, access_criterion, filiation_1, filiation_2', 'length', 'max'=>100),
			array('birthday_date', 'length', 'max'=>10),
			array('cpf, filiation_1_cpf, filiation_2_cpf', 'length', 'max'=>11),
			array('number_ato', 'length', 'max'=>30),
			array('filiation_1_rg, filiation_1_job, filiation_2_rg, filiation_2_job', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('register_type, id, school_inep_id_fk, inep_id, name, email, birthday_date, sex, color_race, nationality, role, residence_zone, access_criterion, contract_type, cpf, number_ato, filiation, filiation_1, filiation_2, filiation_1_rg, filiation_1_cpf, filiation_1_scholarity, filiation_1_job, filiation_2_rg, filiation_2_cpf, filiation_2_scholarity, filiation_2_job, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, users_fk', 'safe', 'on'=>'search'),
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
			'usersFk' => array(self::BELONGS_TO, 'Users', 'users_fk'),
			'schoolInepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'register_type' => Yii::t('default', 'Register Type'),
			'id' => 'ID',
			'school_inep_id_fk' => Yii::t('default', 'School Inep Id Fk'),
			'inep_id' => Yii::t('default', 'Inep'),
			'name' => Yii::t('default', 'Manager Name'),
			'email' => Yii::t('default', 'Manager Email'),
			'birthday_date' => Yii::t('default', 'Birthday Date'),
			'sex' => Yii::t('default', 'Sex'),
			'color_race' =>  Yii::t('default', 'Color Race'),
			'nationality' => Yii::t('default', 'Nationality'),
			'role' => Yii::t('default', 'Manager Role'),
			'residence_zone' => 'Residence Zone',
			'access_criterion' => Yii::t('default', 'Manager Access Criterion'),
			'contract_type' => Yii::t('default', 'Manager Contract Type'),
			'cpf' => Yii::t('default', 'Manager Cpf'),
			'number_ato' => Yii::t('default', 'Number Ato'),
			'filiation' => Yii::t('default', 'Filiation'),
			'filiation_1' => Yii::t('default', 'Filiation 1'),
			'filiation_2' =>  Yii::t('default', 'Filiation 2'),
			'filiation_1_rg' => 'Filiation 1 Rg',
			'filiation_1_cpf' => 'Filiation 1 Cpf',
			'filiation_1_scholarity' => 'Filiation 1 Scholarity',
			'filiation_1_job' => 'Filiation 1 Job',
			'filiation_2_rg' => 'Filiation 2 Rg',
			'filiation_2_cpf' => 'Filiation 2 Cpf',
			'filiation_2_scholarity' => 'Filiation 2 Scholarity',
			'filiation_2_job' => 'Filiation 2 Job',
			'edcenso_nation_fk' => 'Edcenso Nation Fk',
			'edcenso_uf_fk' => 'Edcenso Uf Fk',
			'edcenso_city_fk' => 'Edcenso City Fk',
			'users_fk' => 'Users Fk',
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

		$criteria->compare('register_type',$this->register_type,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);
		$criteria->compare('inep_id',$this->inep_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('birthday_date',$this->birthday_date,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('color_race',$this->color_race);
		$criteria->compare('nationality',$this->nationality);
		$criteria->compare('role',$this->role);
		$criteria->compare('residence_zone',$this->residence_zone);
		$criteria->compare('access_criterion',$this->access_criterion,true);
		$criteria->compare('contract_type',$this->contract_type);
		$criteria->compare('cpf',$this->cpf,true);
		$criteria->compare('number_ato',$this->number_ato,true);
		$criteria->compare('filiation',$this->filiation);
		$criteria->compare('filiation_1',$this->filiation_1,true);
		$criteria->compare('filiation_2',$this->filiation_2,true);
		$criteria->compare('filiation_1_rg',$this->filiation_1_rg,true);
		$criteria->compare('filiation_1_cpf',$this->filiation_1_cpf,true);
		$criteria->compare('filiation_1_scholarity',$this->filiation_1_scholarity);
		$criteria->compare('filiation_1_job',$this->filiation_1_job,true);
		$criteria->compare('filiation_2_rg',$this->filiation_2_rg,true);
		$criteria->compare('filiation_2_cpf',$this->filiation_2_cpf,true);
		$criteria->compare('filiation_2_scholarity',$this->filiation_2_scholarity);
		$criteria->compare('filiation_2_job',$this->filiation_2_job,true);
		$criteria->compare('edcenso_nation_fk',$this->edcenso_nation_fk);
		$criteria->compare('edcenso_uf_fk',$this->edcenso_uf_fk);
		$criteria->compare('edcenso_city_fk',$this->edcenso_city_fk);
		$criteria->compare('users_fk',$this->users_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ManagerIdentification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
