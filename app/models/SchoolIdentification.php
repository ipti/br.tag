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
 * @property integer $private_school_category
 * @property integer $public_contract
 * @property integer $private_school_business_or_individual
 * @property integer $private_school_syndicate_or_association
 * @property integer $private_school_ong_or_oscip
 * @property integer $private_school_non_profit_institutions
 * @property integer $private_school_s_system
 * @property string $private_school_maintainer_cnpj
 * @property string $private_school_cnpj
 * @property integer $regulation
 *
 * The followings are the available model relations:
 * @property SchoolStructure $structure
 * @property Classroom[] $classrooms
 * @property InstructorDocumentsAndAddress[] $instructorDocumentsAndAddresses
 * @property InstructorTeachingData[] $instructorTeachingDatas
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 * @property EdcensoDistrict $edcensoDistrictFk
 * @property StudentDocumentsAndAddress[] $studentDocumentsAndAddresses
 * @property StudentEnrollment[] $studentEnrollments
 * @property StudentIdentification[] $studentIdentifications
 */
class SchoolIdentification extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SchoolIdentification the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'school_identification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, inep_id, cep, address, edcenso_uf_fk, edcenso_city_fk, edcenso_district_fk, administrative_dependence, location,edcenso_regional_education_organ_fk', 'required'),
            array('situation, edcenso_uf_fk, edcenso_city_fk, edcenso_district_fk, administrative_dependence, location, private_school_category, public_contract, private_school_business_or_individual, private_school_syndicate_or_association, private_school_ong_or_oscip, private_school_non_profit_institutions, private_school_s_system, regulation', 'numerical', 'integerOnly' => true),
            array('register_type, ddd', 'length', 'max' => 2),
            array('inep_id, cep, public_phone_number, fax_number', 'length', 'max' => 8),
            array('initial_date, final_date, address_number', 'length', 'max' => 10),
            array('name, address', 'length', 'max' => 100),
            array('latitude, longitude, address_complement', 'length', 'max' => 20),
            array('address_neighborhood, email', 'length', 'max' => 50),
            array('phone_number, other_phone_number', 'length', 'max' => 9),
            array('edcenso_regional_education_organ_fk', 'length', 'max' => 5),
            array('private_school_maintainer_cnpj, private_school_cnpj', 'length', 'max' => 14),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('register_type, inep_id, situation, initial_date, final_date, name, latitude, longitude, cep, address, address_number, address_complement, address_neighborhood, edcenso_uf_fk, edcenso_city_fk, edcenso_district_fk, ddd, phone_number, public_phone_number, other_phone_number, fax_number, email, edcenso_regional_education_organ_fk, administrative_dependence, location, private_school_category, public_contract, private_school_business_or_individual, private_school_syndicate_or_association, private_school_ong_or_oscip, private_school_non_profit_institutions, private_school_s_system, private_school_maintainer_cnpj, private_school_cnpj, regulation', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'classrooms' => array(self::HAS_MANY, 'Classroom', 'school_inep_fk'),
            'instructorDocumentsAndAddresses' => array(self::HAS_MANY, 'InstructorDocumentsAndAddress', 'school_inep_id_fk'),
            'instructorTeachingDatas' => array(self::HAS_MANY, 'InstructorTeachingData', 'school_inep_id_fk'),
            'edcensoUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'),
            'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
            'edcensoDistrictFk' => array(self::BELONGS_TO, 'EdcensoDistrict', 'edcenso_district_fk'),
            'studentDocumentsAndAddresses' => array(self::HAS_MANY, 'StudentDocumentsAndAddress', 'school_inep_id_fk'),
            'studentEnrollments' => array(self::HAS_MANY, 'StudentEnrollment', 'school_inep_id_fk'),
            'studentIdentifications' => array(self::HAS_MANY, 'StudentIdentification', 'school_inep_id_fk'),
            'structure'=> [self::HAS_ONE, 'SchoolStructure','school_inep_id_fk'],
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
            'regulation' => Yii::t('default', 'Regulation'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('register_type', $this->register_type, true);
        $criteria->compare('inep_id', $this->inep_id, true);
        $criteria->compare('situation', $this->situation);
        $criteria->compare('initial_date', $this->initial_date, true);
        $criteria->compare('final_date', $this->final_date, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longitude', $this->longitude, true);
        $criteria->compare('cep', $this->cep, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('address_number', $this->address_number, true);
        $criteria->compare('address_complement', $this->address_complement, true);
        $criteria->compare('address_neighborhood', $this->address_neighborhood, true);
        $criteria->compare('edcenso_uf_fk', $this->edcenso_uf_fk);
        $criteria->compare('edcenso_city_fk', $this->edcenso_city_fk);
        $criteria->compare('edcenso_district_fk', $this->edcenso_district_fk);
        $criteria->compare('ddd', $this->ddd, true);
        $criteria->compare('phone_number', $this->phone_number, true);
        $criteria->compare('public_phone_number', $this->public_phone_number, true);
        $criteria->compare('other_phone_number', $this->other_phone_number, true);
        $criteria->compare('fax_number', $this->fax_number, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('edcenso_regional_education_organ_fk', $this->edcenso_regional_education_organ_fk, true);
        $criteria->compare('administrative_dependence', $this->administrative_dependence);
        $criteria->compare('location', $this->location);
        $criteria->compare('private_school_category', $this->private_school_category);
        $criteria->compare('public_contract', $this->public_contract);
        $criteria->compare('private_school_business_or_individual', $this->private_school_business_or_individual);
        $criteria->compare('private_school_syndicate_or_association', $this->private_school_syndicate_or_association);
        $criteria->compare('private_school_ong_or_oscip', $this->private_school_ong_or_oscip);
        $criteria->compare('private_school_non_profit_institutions', $this->private_school_non_profit_institutions);
        $criteria->compare('private_school_s_system', $this->private_school_s_system);
        $criteria->compare('private_school_maintainer_cnpj', $this->private_school_maintainer_cnpj, true);
        $criteria->compare('private_school_cnpj', $this->private_school_cnpj, true);
        $criteria->compare('regulation', $this->regulation);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => array(
                            'name' => CSort::SORT_ASC
                        ),
                    ),
                    'pagination' => array(
                        'pageSize' => 15,
                    ),
                ));
    }

}
