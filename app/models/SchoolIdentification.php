<?php

/**
 * This is the model class for table "school_identification".
 *
 * The followings are the available columns in table 'school_identification':
 * @property string $register_type
 * @property string $inep_id
 * @property integer $situation
 * @property string $initial_date
 * @property string $final_date
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property string $cep
 * @property string $address
 * @property string $address_number
 * @property string $address_complement
 * @property string $address_neighborhood
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 * @property integer $edcenso_district_fk
 * @property string $ddd
 * @property string $phone_number
 * @property string $public_phone_number
 * @property string $other_phone_number
 * @property string $fax_number
 * @property string $email
 * @property string $edcenso_regional_education_organ_fk
 * @property integer $administrative_dependence
 * @property integer $location
 * @property integer $id_difflocation
 * @property integer $linked_mec
 * @property integer $linked_army
 * @property integer $linked_helth
 * @property integer $linked_other
 * @property integer $private_school_category
 * @property integer $public_contract
 * @property integer $private_school_business_or_individual
 * @property integer $private_school_syndicate_or_association
 * @property integer $private_school_ong_or_oscip
 * @property integer $private_school_non_profit_institutions
 * @property integer $private_school_s_system
 * @property integer $private_school_organization_civil_society
 * @property string $private_school_maintainer_cnpj
 * @property string $private_school_cnpj
 * @property integer $regulation
 * @property integer $regulation_organ
 * @property integer $regulation_organ_federal
 * @property integer $regulation_organ_state
 * @property integer $regulation_organ_municipal
 * @property integer $offer_or_linked_unity
 * @property string $inep_head_school
 * @property string $ies_code
 * @property string $logo_file_name
 * @property string $logo_file_type
 * @property string $logo_file_content
 * @property string $act_of_acknowledgement
 *
 * The followings are the available model relations:
 * @property UnusedtableCurricularMatrix[] $unusedtableCurricularMatrixes
 * @property CalendarEvent[] $calendarEvents
 * @property Classroom[] $classrooms
 * @property CoursePlan[] $coursePlans
 * @property EventPreRegistration[] $eventPreRegistrations
 * @property FoodInventory[] $foodInventories
 * @property InstructorDocumentsAndAddress[] $instructorDocumentsAndAddresses
 * @property InstructorSchool[] $instructorSchools
 * @property InstructorTeachingData[] $instructorTeachingDatas
 * @property Log[] $logs
 * @property LunchInventory[] $lunchInventories
 * @property LunchMenu[] $lunchMenus
 * @property ManagerIdentification[] $managerIdentifications
 * @property Professional[] $professionals
 * @property SchoolConfiguration[] $schoolConfigurations
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 * @property EdcensoDistrict $edcensoDistrictFk
 * @property SchoolStagesConceptGrades[] $schoolStagesConceptGrades
 * @property StagesVacancyPreRegistration[] $stagesVacancyPreRegistrations
 * @property StudentDocumentsAndAddress[] $studentDocumentsAndAddresses
 * @property StudentEnrollment[] $studentEnrollments
 * @property StudentIdentification[] $studentIdentifications
 * @property StudentPreIdentification[] $studentPreIdentifications
 * @property UsersSchool[] $usersSchools
 */
class SchoolIdentification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'school_identification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('inep_id, cep, address, edcenso_uf_fk, edcenso_city_fk, edcenso_district_fk, administrative_dependence, location', 'required'),
			array('situation, edcenso_uf_fk, edcenso_city_fk, edcenso_district_fk, administrative_dependence, location, id_difflocation, linked_mec, linked_army, linked_helth, linked_other, private_school_category, public_contract, private_school_business_or_individual, private_school_syndicate_or_association, private_school_ong_or_oscip, private_school_non_profit_institutions, private_school_s_system, private_school_organization_civil_society, regulation, regulation_organ, regulation_organ_federal, regulation_organ_state, regulation_organ_municipal, offer_or_linked_unity', 'numerical', 'integerOnly'=>true),
			array('register_type, ddd', 'length', 'max'=>2),
			array('inep_id, cep, public_phone_number, fax_number, inep_head_school', 'length', 'max'=>8),
			array('initial_date, final_date, address_number', 'length', 'max'=>10),
			array('name, address, logo_file_name', 'length', 'max'=>100),
			array('latitude, longitude, address_complement', 'length', 'max'=>20),
			array('address_neighborhood, email, logo_file_type', 'length', 'max'=>50),
			array('phone_number, other_phone_number', 'length', 'max'=>9),
			array('edcenso_regional_education_organ_fk', 'length', 'max'=>5),
			array('private_school_maintainer_cnpj, private_school_cnpj, ies_code', 'length', 'max'=>14),
			array('logo_file_content, act_of_acknowledgement', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('register_type, inep_id, situation, initial_date, final_date, name, latitude, longitude, cep, address, address_number, address_complement, address_neighborhood, edcenso_uf_fk, edcenso_city_fk, edcenso_district_fk, ddd, phone_number, public_phone_number, other_phone_number, fax_number, email, edcenso_regional_education_organ_fk, administrative_dependence, location, id_difflocation, linked_mec, linked_army, linked_helth, linked_other, private_school_category, public_contract, private_school_business_or_individual, private_school_syndicate_or_association, private_school_ong_or_oscip, private_school_non_profit_institutions, private_school_s_system, private_school_organization_civil_society, private_school_maintainer_cnpj, private_school_cnpj, regulation, regulation_organ, regulation_organ_federal, regulation_organ_state, regulation_organ_municipal, offer_or_linked_unity, inep_head_school, ies_code, logo_file_name, logo_file_type, logo_file_content, act_of_acknowledgement', 'safe', 'on'=>'search'),
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
			'unusedtableCurricularMatrixes' => array(self::HAS_MANY, 'UnusedtableCurricularMatrix', 'school_fk'),
			'calendarEvents' => array(self::HAS_MANY, 'CalendarEvent', 'school_fk'),
			'classrooms' => array(self::HAS_MANY, 'Classroom', 'school_inep_fk'),
			'coursePlans' => array(self::HAS_MANY, 'CoursePlan', 'school_inep_fk'),
			'eventPreRegistrations' => array(self::HAS_MANY, 'EventPreRegistration', 'school_inep_id_fk'),
			'foodInventories' => array(self::HAS_MANY, 'FoodInventory', 'school_fk'),
			'instructorDocumentsAndAddresses' => array(self::HAS_MANY, 'InstructorDocumentsAndAddress', 'school_inep_id_fk'),
			'instructorSchools' => array(self::HAS_MANY, 'InstructorSchool', 'school_fk'),
			'instructorTeachingDatas' => array(self::HAS_MANY, 'InstructorTeachingData', 'school_inep_id_fk'),
			'logs' => array(self::HAS_MANY, 'Log', 'school_fk'),
			'lunchInventories' => array(self::HAS_MANY, 'LunchInventory', 'school_fk'),
			'lunchMenus' => array(self::HAS_MANY, 'LunchMenu', 'school_fk'),
			'managerIdentifications' => array(self::HAS_MANY, 'ManagerIdentification', 'school_inep_id_fk'),
			'professionals' => array(self::HAS_MANY, 'Professional', 'inep_id_fk'),
			'schoolConfigurations' => array(self::HAS_MANY, 'SchoolConfiguration', 'school_inep_id_fk'),
			'edcensoUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'),
			'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
			'edcensoDistrictFk' => array(self::BELONGS_TO, 'EdcensoDistrict', 'edcenso_district_fk'),
			'schoolStagesConceptGrades' => array(self::HAS_MANY, 'SchoolStagesConceptGrades', 'school_fk'),
			'stagesVacancyPreRegistrations' => array(self::HAS_MANY, 'StagesVacancyPreRegistration', 'school_inep_id_fk'),
			'studentDocumentsAndAddresses' => array(self::HAS_MANY, 'StudentDocumentsAndAddress', 'school_inep_id_fk'),
			'studentEnrollments' => array(self::HAS_MANY, 'StudentEnrollment', 'school_inep_id_fk'),
			'studentIdentifications' => array(self::HAS_MANY, 'StudentIdentification', 'school_inep_id_fk'),
			'studentPreIdentifications' => array(self::HAS_MANY, 'StudentPreIdentification', 'school_inep_id_fk'),
			'usersSchools' => array(self::HAS_MANY, 'UsersSchool', 'school_fk'),
		);
	}

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'register_type' => Yii::t('default', 'Register Type'),
            'inep_id' => Yii::t('default', 'Inep'),
            'situation' => Yii::t('default', 'Situation'),
            'initial_date' => Yii::t('default', 'Initial Date'),
            'final_date' => Yii::t('default', 'Final Date'),
            'name' => Yii::t('default', 'Name'),
            'latitude' => Yii::t('default', 'Latitude'),
            'longitude' => Yii::t('default', 'Longitude'),
            'cep' => Yii::t('default', 'Cep'),
            'address' => Yii::t('default', 'Address'),
            'address_number' => Yii::t('default', 'Address Number'),
            'address_complement' => Yii::t('default', 'Address Complement'),
            'address_neighborhood' => Yii::t('default', 'Address Neighborhood'),
            'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
            'edcenso_city_fk' => Yii::t('default', 'Edcenso City Fk'),
            'edcenso_district_fk' => Yii::t('default', 'Edcenso District Fk'),
            'ddd' => Yii::t('default', 'Ddd'),
            'phone_number' => Yii::t('default', 'Phone Number'),
            'public_phone_number' => Yii::t('default', 'Public Phone Number'),
            'other_phone_number' => Yii::t('default', 'Other Phone Number'),
            'fax_number' => Yii::t('default', 'Fax Number'),
            'email' => Yii::t('default', 'Email'),
            'edcenso_regional_education_organ_fk' => Yii::t('default', 'Edcenso Regional Education Organ Fk'),
            'administrative_dependence' => Yii::t('default', 'Administrative Dependence'),
            'location' => Yii::t('default', 'Location'),
            'private_school_category' => Yii::t('default', 'Private School Category'),
            'public_contract' => Yii::t('default', 'Public Contract'),
            'private_school_business_or_individual' => Yii::t('default', 'Private School Business Or Individual'),
            'private_school_syndicate_or_association' => Yii::t('default', 'Private School Syndicate Or Association'),
            'private_school_ong_or_oscip' => Yii::t('default', 'Private School Ong Or Oscip'),
            'private_school_non_profit_institutions' => Yii::t('default', 'Private School Non Profit Institutions'),
            'private_school_s_system' => Yii::t('default', 'Private School S System'),
            'private_school_maintainer_cnpj' => Yii::t('default', 'Private School Maintainer Cnpj'),
            'private_school_cnpj' => Yii::t('default', 'Private School Cnpj'),
            'offer_or_linked_unity' => Yii::t('default', 'Offer Or Linked Unity'),
            'inep_head_school' => Yii::t('default', 'Inep Head School'),
            'ies_code' => Yii::t('default', 'Ies Code'),
            'regulation' => Yii::t('default', 'Regulation'),
            'act_of_acknowledgement' => Yii::t('default', 'Act of acknowledgement'),
            'logo_file_content' => Yii::t('default', 'Logo'),
            'id_difflocation' => Yii::t('default', 'Id difflocation'),
            'linked_mec' => Yii::t('default', 'Linked MEC'),
            'linked_army' => Yii::t('default', 'Linked Army'),
            'linked_helth' => Yii::t('default', 'Linked health'),
            'linked_other' => Yii::t('default', 'LInked other'),
            'regulation_organ' => Yii::t('default', 'Regulation Organ'),
            'regulation_organ_federal' => Yii::t('default', 'Regulation Organ Federal'),
            'regulation_organ_state' => Yii::t('default', 'Regulation Organ State'),
            'regulation_organ_municipal' => Yii::t('default', 'Regulation Organ Municipal'),
            'private_school_organization_civil_society' => Yii::t('default', 'Organization Civil Society'),
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
		$criteria->compare('inep_id',$this->inep_id,true);
		$criteria->compare('situation',$this->situation);
		$criteria->compare('initial_date',$this->initial_date,true);
		$criteria->compare('final_date',$this->final_date,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('cep',$this->cep,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address_number',$this->address_number,true);
		$criteria->compare('address_complement',$this->address_complement,true);
		$criteria->compare('address_neighborhood',$this->address_neighborhood,true);
		$criteria->compare('edcenso_uf_fk',$this->edcenso_uf_fk);
		$criteria->compare('edcenso_city_fk',$this->edcenso_city_fk);
		$criteria->compare('edcenso_district_fk',$this->edcenso_district_fk);
		$criteria->compare('ddd',$this->ddd,true);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('public_phone_number',$this->public_phone_number,true);
		$criteria->compare('other_phone_number',$this->other_phone_number,true);
		$criteria->compare('fax_number',$this->fax_number,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('edcenso_regional_education_organ_fk',$this->edcenso_regional_education_organ_fk,true);
		$criteria->compare('administrative_dependence',$this->administrative_dependence);
		$criteria->compare('location',$this->location);
		$criteria->compare('id_difflocation',$this->id_difflocation);
		$criteria->compare('linked_mec',$this->linked_mec);
		$criteria->compare('linked_army',$this->linked_army);
		$criteria->compare('linked_helth',$this->linked_helth);
		$criteria->compare('linked_other',$this->linked_other);
		$criteria->compare('private_school_category',$this->private_school_category);
		$criteria->compare('public_contract',$this->public_contract);
		$criteria->compare('private_school_business_or_individual',$this->private_school_business_or_individual);
		$criteria->compare('private_school_syndicate_or_association',$this->private_school_syndicate_or_association);
		$criteria->compare('private_school_ong_or_oscip',$this->private_school_ong_or_oscip);
		$criteria->compare('private_school_non_profit_institutions',$this->private_school_non_profit_institutions);
		$criteria->compare('private_school_s_system',$this->private_school_s_system);
		$criteria->compare('private_school_organization_civil_society',$this->private_school_organization_civil_society);
		$criteria->compare('private_school_maintainer_cnpj',$this->private_school_maintainer_cnpj,true);
		$criteria->compare('private_school_cnpj',$this->private_school_cnpj,true);
		$criteria->compare('regulation',$this->regulation);
		$criteria->compare('regulation_organ',$this->regulation_organ);
		$criteria->compare('regulation_organ_federal',$this->regulation_organ_federal);
		$criteria->compare('regulation_organ_state',$this->regulation_organ_state);
		$criteria->compare('regulation_organ_municipal',$this->regulation_organ_municipal);
		$criteria->compare('offer_or_linked_unity',$this->offer_or_linked_unity);
		$criteria->compare('inep_head_school',$this->inep_head_school,true);
		$criteria->compare('ies_code',$this->ies_code,true);
		$criteria->compare('logo_file_name',$this->logo_file_name,true);
		$criteria->compare('logo_file_type',$this->logo_file_type,true);
		$criteria->compare('logo_file_content',$this->logo_file_content,true);
		$criteria->compare('act_of_acknowledgement',$this->act_of_acknowledgement,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SchoolIdentification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
