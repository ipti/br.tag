<?php

/**
 * This is the model class for table "professional_allocation".
 *
 * The followings are the available columns in table 'professional_allocation':
 * @property integer $id
 * @property integer $professional_fk
 * @property string $location_type
 * @property string $location_name
 * @property string $school_inep_fk
 * @property integer $role
 * @property integer $contract_type
 * @property integer $workload
 * @property integer $school_year
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Professional $professional
 * @property SchoolIdentification $school
 */
class ProfessionalAllocation extends TagModel
{
    // Professional Roles
    const ROLE_GARDEN_PLANTING = 1;
    const ROLE_ADMIN_ASSISTANT = 2;
    const ROLE_SERVICE_ASSISTANT = 3;
    const ROLE_LIBRARIAN = 4;
    const ROLE_FIREFIGHTER = 5;
    const ROLE_COORDINATOR_SHIFT = 6;
    const ROLE_SPEECH_THERAPIST = 7;
    const ROLE_NUTRITIONIST = 8;
    const ROLE_PSYCHOLOGIST = 9;
    const ROLE_COOKER = 10;
    const ROLE_SUPPORT_PROFESSIONAL = 11;
    const ROLE_SCHOOL_SECRETARY = 12;
    const ROLE_SECURITY_GUARD = 13;
    const ROLE_MONITOR = 14;
    const ROLE_BRAILLE_ASSISTANT = 15;
    const ROLE_UNDEFINED = 9999;

    // Contract Types
    const CONTRACT_EFETIVO = 1;
    const CONTRACT_TEMPORARIO = 2;
    const CONTRACT_CLT = 3;
    const CONTRACT_TERCEIRIZADO = 4;
    const CONTRACT_UNDEFINED = 99;

    // Status
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'professional_allocation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['professional_fk, role, contract_type, workload, school_year, location_type, status', 'required'],
            ['school_inep_fk', 'required', 'on' => 'school_location'],
            ['location_name', 'required', 'on' => 'other_location'],
            ['professional_fk, role, contract_type, workload, school_year, status', 'numerical', 'integerOnly' => true],
            ['school_inep_fk', 'length', 'max' => 8],
            ['location_name', 'length', 'max' => 255],
            ['location_type', 'in', 'range' => ['school', 'secretariat', 'other']],
            ['id, professional_fk, location_type, location_name, school_inep_fk, role, contract_type, workload, school_year, status', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'professional' => [self::BELONGS_TO, 'Professional', 'professional_fk'],
            'school' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'professional_fk' => 'Profissional',
            'location_type' => 'Tipo de Local',
            'location_name' => 'Nome do Local',
            'school_inep_fk' => 'Escola',
            'role' => 'Cargo/Função',
            'contract_type' => 'Tipo de Contrato',
            'workload' => 'Carga Horária (h)',
            'school_year' => 'Ano Letivo',
            'status' => 'Situação',
        ];
    }

    public static function getRoleOptions()
    {
        return [
            self::ROLE_GARDEN_PLANTING => 'Técnico em Horta/Plantio/Agricultura',
            self::ROLE_ADMIN_ASSISTANT => 'Auxiliar Administrativo',
            self::ROLE_SERVICE_ASSISTANT => 'Auxiliar de Serviços Gerais',
            self::ROLE_LIBRARIAN => 'Bibliotecário',
            self::ROLE_FIREFIGHTER => 'Bombeiro',
            self::ROLE_COORDINATOR_SHIFT => 'Coordenador de Turno',
            self::ROLE_SPEECH_THERAPIST => 'Fonoaudiólogo',
            self::ROLE_NUTRITIONIST => 'Nutricionista',
            self::ROLE_PSYCHOLOGIST => 'Psicólogo',
            self::ROLE_COOKER => 'Cozinheiro / Merendeira',
            self::ROLE_SUPPORT_PROFESSIONAL => 'Profissional de Apoio Pedagógico',
            self::ROLE_SCHOOL_SECRETARY => 'Secretário(a)',
            self::ROLE_SECURITY_GUARD => 'Segurança',
            self::ROLE_MONITOR => 'Monitor',
            self::ROLE_BRAILLE_ASSISTANT => 'Assistente ou Revisor em Braille',
            self::ROLE_UNDEFINED => 'Não Definido (Necessita Correção)',
        ];
    }

    public static function getContractTypeOptions()
    {
        return [
            self::CONTRACT_EFETIVO => 'Concursado / Efetivo / Estável',
            self::CONTRACT_TEMPORARIO => 'Contrato Temporário',
            self::CONTRACT_CLT => 'Emprego Público (CLT)',
            self::CONTRACT_TERCEIRIZADO => 'Terceirizado',
            self::CONTRACT_UNDEFINED => 'Não Definido (Necessita Correção)',
        ];
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Ativo',
            self::STATUS_INACTIVE => 'Inativo',
        ];
    }

    public static function getLocationTypeOptions()
    {
        return [
            'school' => 'Escola',
            'secretariat' => 'Secretaria de Educação',
            'other' => 'Outro',
        ];
    }

    public function getLocationDisplay()
    {
        if ($this->location_type === 'school' && $this->school) {
            return $this->school->name;
        }
        return $this->location_name;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProfessionalAllocation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
