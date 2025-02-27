<?php

require_once __DIR__ ."/../extensions/Validator/StudentDocumentsAndAddressValidation.php";
/**
 * This is the model class for table "student_documents_and_address".
 *
 * The followings are the available columns in table 'student_documents_and_address':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $gov_id
 * @property string $student_fk
 * @property integer $id
 * @property string $rg_number
 * @property integer $rg_number_edcenso_organ_id_emitter_fk
 * @property integer $rg_number_edcenso_uf_fk
 * @property string $rg_number_expediction_date
 * @property integer $civil_certification
 * @property integer $civil_certification_type
 * @property integer $civil_certification_type_old
 * @property integer $civil_certification_type_new
 * @property string $civil_certification_term_number
 * @property string $civil_certification_sheet
 * @property string $civil_certification_book
 * @property string $civil_certification_date
 * @property integer $notary_office_uf_fk
 * @property integer $notary_office_city_fk
 * @property integer $edcenso_notary_office_fk
 * @property string $civil_register_enrollment_number
 * @property string $cpf
 * @property string $foreign_document_or_passport
 * @property string $nis
 * @property integer $residence_zone
 * @property string $cep
 * @property string $address
 * @property string $number
 * @property string $complement
 * @property string $neighborhood
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 * @property integer $received_cc
 * @property integer $received_address
 * @property integer $received_photo
 * @property integer $received_nis
 * @property integer $received_history
 * @property integer $received_responsable_rg
 * @property integer $received_responsable_cpf
 * @property integer $received_student_cpf
 * @property integer $received_student_rg
 * @property integer $received_sus_card
 * @property string $cns
 * @property string $fkid
 * @property integer $justice_restriction
 * @property integer $diff_location
 * @property integer $consent_form
 * @property integer $justification
 * @property string $DNE_city_code
 * @property int $cpf_reason
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolInepIdFk
 * @property EdcensoOrganIdEmitter $rgNumberEdcensoOrganIdEmitterFk
 * @property EdcensoUf $rgNumberEdcensoUfFk
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 * @property EdcensoUf $notaryOfficeUfFk
 * @property EdcensoCity $notaryOfficeCityFk
 */
class StudentDocumentsAndAddress extends AltActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return StudentDocumentsAndAddress the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'student_documents_and_address';
    }



    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_inep_id_fk, residence_zone', 'required'),
            array('rg_number_edcenso_organ_id_emitter_fk, rg_number_edcenso_uf_fk, civil_certification, civil_certification_type, notary_office_uf_fk, notary_office_city_fk, edcenso_notary_office_fk, residence_zone, edcenso_uf_fk, edcenso_city_fk, received_cc, received_address, received_photo, received_nis, received_history, received_responsable_rg, received_responsable_cpf, received_student_cpf, received_student_rg, received_sus_card, justice_restriction, diff_location, consent_form, cpf_reason', 'numerical', 'integerOnly'=>true),
            array('register_type', 'length', 'max'=>2),
            array('school_inep_id_fk, civil_certification_term_number, civil_certification_book, cep', 'length', 'max'=>8),
            array('student_fk', 'length', 'max'=>12),
            array('rg_number, foreign_document_or_passport, complement, cns', 'length', 'max'=>20),
            array('rg_number_expediction_date, civil_certification_date, number', 'length', 'max'=>10),
            array('civil_certification_sheet', 'length', 'max'=>4),
            array('civil_register_enrollment_number', 'length', 'max'=>32),
            array('cpf, nis', 'length', 'max'=>11),
            array('address', 'required', 'message' => 'O campo de endereço é obrigatório.', 'on' => 'SEDSP'),
            array('address', 'length', 'max' => 100, 'tooLong' => 'O endereço deve ter no máximo 100 caracteres.'),
            array('neighborhood', 'length', 'max'=>50),
            array('hash', 'length', 'max'=>40),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('register_type, school_inep_id_fk, student_fk, id, rg_number, rg_number_edcenso_organ_id_emitter_fk, rg_number_edcenso_uf_fk, rg_number_expediction_date, civil_certification, civil_certification_type, civil_certification_term_number, civil_certification_sheet, civil_certification_book, civil_certification_date, notary_office_uf_fk, notary_office_city_fk, edcenso_notary_office_fk, civil_register_enrollment_number, cpf, foreign_document_or_passport, nis, residence_zone, cep, address, number, complement, neighborhood, edcenso_uf_fk, edcenso_city_fk, received_cc, received_address, received_photo, received_nis, received_history, received_responsable_rg, received_responsable_cpf, received_student_cpf, received_student_rg, received_sus_card, cns, fkid, justice_restriction, diff_location, cpf_reason', 'safe', 'on'=>'search'),
            array('cpf', 'validateCPF', 'except' => "censoimport"),
            array('civil_register_enrollment_number', 'validateCivilRegister', 'except' => "censoimport"),

        );
    }

    public function validateCPF($cpfStudent)
    {
        $cpfStu = $this->$cpfStudent;

        if($cpfStu === "") {
            return true;
        }

        $cpf = preg_replace('/[^0-9]/', '', $cpfStu);

        if (strlen($cpf) !== 11) {
            $this->addError($cpfStudent, 'O CPF deve conter exatamente 11 números.');
            return;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $this->addError($cpfStudent, 'O CPF não pode ter todos os dígitos iguais.');
            return;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $this->addError($cpfStudent, 'O CPF é inválido.');
                return;
            }
        }
        return true;
    }

    public function validateCivilRegister($civilRegisterNumber)
    {
        $civilRegister = $this->$civilRegisterNumber;

        $modelIdentification = new StudentIdentification;
        $documentsValidation = new StudentDocumentsAndAddressValidation();

        $validatorRegister = $documentsValidation->isCivilRegisterNumberValid(
            $civilRegister,
            $modelIdentification->birthday
        );

        $statusRegisterCivil = $validatorRegister['status'];
        $errorRegisterCivil = $validatorRegister['erro'];

        if ($statusRegisterCivil && empty($errorRegisterCivil)) {
            return true;
        }

        if (!empty($civilRegister)) {
            $this->addError($civilRegister, 'Certidão de nascimento: ' . ucfirst($errorRegisterCivil));
        }
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
            'rgNumberEdcensoOrganIdEmitterFk' => array(self::BELONGS_TO, 'EdcensoOrganIdEmitter', 'rg_number_edcenso_organ_id_emitter_fk'),
            'rgNumberEdcensoUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'rg_number_edcenso_uf_fk'),
            'edcensoUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'),
            'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
            'notaryOfficeUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'notary_office_uf_fk'),
            'notaryOfficeCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'notary_office_city_fk'),
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
            'student_fk' => Yii::t('default', 'Student Fk'),
            'id' => Yii::t('default', 'ID'),
            'rg_number' => Yii::t('default', 'Rg Number'),
            'rg_number_edcenso_organ_id_emitter_fk' => Yii::t('default', 'Rg Number Edcenso Organ Id Emitter Fk'),
            'rg_number_edcenso_uf_fk' => Yii::t('default', 'Rg Number Edcenso Uf Fk'),
            'rg_number_expediction_date' => Yii::t('default', 'Rg Number Expediction Date'),
            'civil_certification' => Yii::t('default', 'Civil Certification'),
            'civil_certification_type' => Yii::t('default', 'Civil Certification Type'),
            'civil_certification_term_number' => Yii::t('default', 'Civil Certification Term Number'),
            'civil_certification_sheet' => Yii::t('default', 'Civil Certification Sheet'),
            'civil_certification_book' => Yii::t('default', 'Civil Certification Book'),
            'civil_certification_date' => Yii::t('default', 'Civil Certification Date'),
            'notary_office_uf_fk' => Yii::t('default', 'Notary Office Uf Fk'),
            'notary_office_city_fk' => Yii::t('default', 'Notary Office City Fk'),
            'edcenso_notary_office_fk' => Yii::t('default', 'Edcenso Notary Office Fk'),
            'civil_register_enrollment_number' => Yii::t('default', 'Civil Register Enrollment Number'),
            'cpf' => Yii::t('default', 'Cpf'),
            'foreign_document_or_passport' => Yii::t('default', 'Foreign Document Or Passport'),
            'nis' => Yii::t('default', 'Nis'),
            'cns' => Yii::t('default', 'CNS Number'),
            'residence_zone' => Yii::t('default', 'Residence Zone'),
            'cep' => Yii::t('default', 'Cep'),
            'address' => Yii::t('default', 'Address'),
            'number' => Yii::t('default', 'Number'),
            'complement' => Yii::t('default', 'Complement'),
            'neighborhood' => Yii::t('default', 'Neighborhood'),
            'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
            'edcenso_city_fk' => Yii::t('default', 'Edcenso City Fk'),
            'received_cc' => Yii::t('default', 'Received Civil Certificate'),
            'received_address' => Yii::t('default', 'Received Receipt Address'),
            'received_photo' => Yii::t('default', 'Received Photo'),
            'received_nis' => Yii::t('default', 'Received NIS'),
            'received_history' => Yii::t('default', 'Received History'),
            'received_responsable_rg' => Yii::t('default', 'Received Responsable`s RG'),
            'received_responsable_cpf' => Yii::t('default', 'Received Responsable`s CPF'),
            'received_student_cpf' => Yii::t('default', 'Received Student CPF'),
            'received_student_rg' => Yii::t('default', 'Received Student RG'),
            'received_sus_card' => Yii::t('default', 'Received SUS Card'),
            'justice_restriction' => Yii::t('default', 'Justice Restriction'),
            'diff_location' => Yii::t('default', 'Diff Location'),
            'consent_form' => Yii::t('default', 'Consent Form'),
            'cpf_reason' => Yii::t('default',"Justification for absence of CPF")
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

        $criteria = new CDbCriteria;

        $criteria->compare('register_type', $this->register_type, true);
        $criteria->compare('school_inep_id_fk', $this->school_inep_id_fk, true);
        $criteria->compare('student_fk', $this->student_fk, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('rg_number', $this->rg_number, true);
        $criteria->compare('rg_number_complement', $this->rg_number_complement, true);
        $criteria->compare('rg_number_edcenso_organ_id_emitter_fk', $this->rg_number_edcenso_organ_id_emitter_fk);
        $criteria->compare('rg_number_edcenso_uf_fk', $this->rg_number_edcenso_uf_fk);
        $criteria->compare('rg_number_expediction_date', $this->rg_number_expediction_date, true);
        $criteria->compare('civil_certification', $this->civil_certification);
        $criteria->compare('civil_certification_type', $this->civil_certification_type);
        $criteria->compare('civil_certification_term_number', $this->civil_certification_term_number, true);
        $criteria->compare('civil_certification_sheet', $this->civil_certification_sheet, true);
        $criteria->compare('civil_certification_book', $this->civil_certification_book, true);
        $criteria->compare('civil_certification_date', $this->civil_certification_date, true);
        $criteria->compare('notary_office_uf_fk', $this->notary_office_uf_fk);
        $criteria->compare('notary_office_city_fk', $this->notary_office_city_fk);
        $criteria->compare('edcenso_notary_office_fk', $this->edcenso_notary_office_fk);
        $criteria->compare('civil_register_enrollment_number', $this->civil_register_enrollment_number, true);
        $criteria->compare('cpf', $this->cpf, true);
        $criteria->compare('foreign_document_or_passport', $this->foreign_document_or_passport, true);
        $criteria->compare('nis', $this->nis, true);
        $criteria->compare('document_failure_lack', $this->document_failure_lack);
        $criteria->compare('residence_zone', $this->residence_zone);
        $criteria->compare('cep', $this->cep, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('complement', $this->complement, true);
        $criteria->compare('neighborhood', $this->neighborhood, true);
        $criteria->compare('edcenso_uf_fk', $this->edcenso_uf_fk);
        $criteria->compare('edcenso_city_fk', $this->edcenso_city_fk);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeSave()
    {

        return parent::beforeSave();
    }
}
