<?php

/**
 * This is the model class for table "enrollment_online_student_identification".
 *
 * The followings are the available columns in table 'enrollment_online_student_identification':
 * @property string $school_inep_id_fk
 * @property string $classroom_inep_id
 * @property integer $classroom_fk
 * @property integer $id
 * @property string $name
 * @property string $birthday
 * @property string $cpf
 * @property integer $sex
 * @property integer $color_race
 * @property integer $deficiency
 * @property integer $deficiency_type_blindness
 * @property integer $deficiency_type_low_vision
 * @property integer $deficiency_type_deafness
 * @property integer $deficiency_type_disability_hearing
 * @property integer $deficiency_type_deafblindness
 * @property integer $deficiency_type_phisical_disability
 * @property integer $deficiency_type_intelectual_disability
 * @property integer $deficiency_type_multiple_disabilities
 * @property integer $deficiency_type_autism
 * @property integer $deficiency_type_gifted
 * @property string $last_change
 * @property string $mother_name
 * @property string $father_name
 * @property string $responsable_name
 * @property string $responsable_cpf
 * @property string $responsable_telephone
 * @property string $cep
 * @property string $address
 * @property string $number
 * @property string $complement
 * @property string $neighborhood
 * @property integer $zone
 * @property integer $edcenso_city_fk
 * @property integer $edcenso_uf_fk
 * @property integer $unavailable
 * @property integer $status_fk
 * @property integer $student_fk
 * @property integer $edcenso_stage_vs_modality_fk
 * @property integer $event_pre_registration_fk
 * @property integer $stages_vacancy_pre_registration_fk
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property EdcensoCity $edcensoCityFk
 * @property Classroom $classroomFk
 * @property EventPreRegistration $eventPreRegistrationFk
 * @property SchoolIdentification $schoolInepIdFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property StagesVacancyPreRegistration $stagesVacancyPreRegistrationFk
 * @property StudentPreIdentificationStatus $statusFk
 * @property StudentIdentification $studentFk
 * @property EdcensoUf $edcensoUfFk
 */
class EnrollmentOnlineStudentIdentification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'enrollment_online_student_identification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk, name, birthday, sex, color_race, deficiency', 'required'),
			array('classroom_fk, sex, color_race, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities, deficiency_type_autism, deficiency_type_gifted, zone, edcenso_city_fk, edcenso_uf_fk, unavailable, status_fk, student_fk, edcenso_stage_vs_modality_fk, event_pre_registration_fk, stages_vacancy_pre_registration_fk', 'numerical', 'integerOnly'=>true),
			array('school_inep_id_fk, cep', 'length', 'max'=>8),
			array('classroom_inep_id', 'length', 'max'=>12),
			array('name, address, complement, neighborhood', 'length', 'max'=>100),
			array('birthday, number', 'length', 'max'=>10),
			array('cpf, responsable_cpf, responsable_telephone', 'length', 'max'=>11),
			array('mother_name, father_name, responsable_name', 'length', 'max'=>90),
			array('last_change, created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('school_inep_id_fk, classroom_inep_id, classroom_fk, id, name, birthday, cpf, sex, color_race, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities, deficiency_type_autism, deficiency_type_gifted, last_change, mother_name, father_name, responsable_name, responsable_cpf, responsable_telephone, cep, address, number, complement, neighborhood, zone, edcenso_city_fk, edcenso_uf_fk, unavailable, status_fk, student_fk, edcenso_stage_vs_modality_fk, event_pre_registration_fk, stages_vacancy_pre_registration_fk, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
			'classroomFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_fk'),
			'eventPreRegistrationFk' => array(self::BELONGS_TO, 'EventPreRegistration', 'event_pre_registration_fk'),
			'schoolInepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'),
			'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
			'stagesVacancyPreRegistrationFk' => array(self::BELONGS_TO, 'StagesVacancyPreRegistration', 'stages_vacancy_pre_registration_fk'),
			'statusFk' => array(self::BELONGS_TO, 'StudentPreIdentificationStatus', 'status_fk'),
			'studentFk' => array(self::BELONGS_TO, 'StudentIdentification', 'student_fk'),
			'edcensoUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'school_inep_id_fk' => 'Escola (INEP)',
            'classroom_inep_id' => 'Sala de Aula (INEP)',
            'classroom_fk' => 'Sala de Aula',
            'id' => 'ID',
            'name' => 'Nome',
            'birthday' => 'Data de Nascimento',
            'cpf' => 'CPF',
            'sex' => 'Sexo',
            'color_race' => 'Cor/Raça',
            'deficiency' => 'Possui Deficiência',
            'deficiency_type_blindness' => 'Cegueira',
            'deficiency_type_low_vision' => 'Baixa Visão',
            'deficiency_type_deafness' => 'Surdez',
            'deficiency_type_disability_hearing' => 'Deficiência Auditiva',
            'deficiency_type_deafblindness' => 'Surdocegueira',
            'deficiency_type_phisical_disability' => 'Deficiência Física',
            'deficiency_type_intelectual_disability' => 'Deficiência Intelectual',
            'deficiency_type_multiple_disabilities' => 'Deficiências Múltiplas',
            'deficiency_type_autism' => 'Autismo',
            'deficiency_type_gifted' => 'Superdotação',
            'last_change' => 'Última Alteração',
            'mother_name' => 'Nome da Mãe',
            'father_name' => 'Nome do Pai',
            'responsable_name' => 'Nome do Responsável',
            'responsable_cpf' => 'CPF do Responsável',
            'responsable_telephone' => 'Telefone do Responsável',
            'cep' => 'CEP',
            'address' => 'Endereço',
            'number' => 'N°',
            'complement' => 'Complemento',
            'neighborhood' => 'Bairro / Povoado',
            'zone' => 'Zona',
            'edcenso_city_fk' => 'Cidade',
            'edcenso_uf_fk' => 'Estado',
            'unavailable' => 'Indisponível',
            'status_fk' => 'Status',
            'student_fk' => 'Aluno',
            'edcenso_stage_vs_modality_fk' => 'Etapa',
            'event_pre_registration_fk' => 'Evento de Pré-Matrícula',
            'stages_vacancy_pre_registration_fk' => 'Etapa/Vaga da Pré-Matrícula',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
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

		$criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);
		$criteria->compare('classroom_inep_id',$this->classroom_inep_id,true);
		$criteria->compare('classroom_fk',$this->classroom_fk);
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('cpf',$this->cpf,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('color_race',$this->color_race);
		$criteria->compare('deficiency',$this->deficiency);
		$criteria->compare('deficiency_type_blindness',$this->deficiency_type_blindness);
		$criteria->compare('deficiency_type_low_vision',$this->deficiency_type_low_vision);
		$criteria->compare('deficiency_type_deafness',$this->deficiency_type_deafness);
		$criteria->compare('deficiency_type_disability_hearing',$this->deficiency_type_disability_hearing);
		$criteria->compare('deficiency_type_deafblindness',$this->deficiency_type_deafblindness);
		$criteria->compare('deficiency_type_phisical_disability',$this->deficiency_type_phisical_disability);
		$criteria->compare('deficiency_type_intelectual_disability',$this->deficiency_type_intelectual_disability);
		$criteria->compare('deficiency_type_multiple_disabilities',$this->deficiency_type_multiple_disabilities);
		$criteria->compare('deficiency_type_autism',$this->deficiency_type_autism);
		$criteria->compare('deficiency_type_gifted',$this->deficiency_type_gifted);
		$criteria->compare('last_change',$this->last_change,true);
		$criteria->compare('mother_name',$this->mother_name,true);
		$criteria->compare('father_name',$this->father_name,true);
		$criteria->compare('responsable_name',$this->responsable_name,true);
		$criteria->compare('responsable_cpf',$this->responsable_cpf,true);
		$criteria->compare('responsable_telephone',$this->responsable_telephone,true);
		$criteria->compare('cep',$this->cep,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('complement',$this->complement,true);
		$criteria->compare('neighborhood',$this->neighborhood,true);
		$criteria->compare('zone',$this->zone);
		$criteria->compare('edcenso_city_fk',$this->edcenso_city_fk);
		$criteria->compare('edcenso_uf_fk',$this->edcenso_uf_fk);
		$criteria->compare('unavailable',$this->unavailable);
		$criteria->compare('status_fk',$this->status_fk);
		$criteria->compare('student_fk',$this->student_fk);
		$criteria->compare('edcenso_stage_vs_modality_fk',$this->edcenso_stage_vs_modality_fk);
		$criteria->compare('event_pre_registration_fk',$this->event_pre_registration_fk);
		$criteria->compare('stages_vacancy_pre_registration_fk',$this->stages_vacancy_pre_registration_fk);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EnrollmentOnlineStudentIdentification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
