<?php

/**
 * This is the model class for table "student_identification".
 *
 * The followings are the available columns in table 'student_identification':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $inep_id
 * @property string $gov_id
 * @property integer $id
 * @property string $name
 * @property string $civil_name
 * @property string $birthday
 * @property integer $sex
 * @property integer $color_race
 * @property integer $filiation
 * @property integer $filiation_no_declared
 * @property integer $filiation_with_and_father
 * @property integer $id_email
 * @property integer $scholarity
 * @property string $filiation_1
 * @property string $filiation_1_cpf
 * @property string $filiation_1_birthday
 * @property string $filiation_1_rg
 * @property string $filiation_1_scholarity
 * @property string $filiation_1_job
 * @property string $filiation_2
 * @property string $filiation_2_cpf
 * @property string $filiation_2_birthday
 * @property string $filiation_2_rg
 * @property string $filiation_2_scholarity
 * @property string $filiation_2_job
 * @property integer $nationality
 * @property integer $state
 * @property integer $city
 * @property string $uf
 * @property integer $edcenso_nation_fk
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 * @property integer $deficiency
 * @property integer $deficiency_type_blindness
 * @property integer $deficiency_type_low_vision
 * @property integer $deficiency_type_monocular_vision
 * @property integer $deficiency_type_deafness
 * @property integer $deficiency_type_disability_hearing
 * @property integer $deficiency_type_deafblindness
 * @property integer $deficiency_type_phisical_disability
 * @property integer $deficiency_type_intelectual_disability
 * @property integer $deficiency_type_multiple_disabilities
 * @property integer $deficiency_type_autism
 * @property integer $deficiency_type_aspenger_syndrome
 * @property integer $deficiency_type_rett_syndrome
 * @property integer $deficiency_type_childhood_disintegrative_disorder
 * @property integer $deficiency_type_gifted
 * @property integer $resource_aid_lector
 * @property integer $resource_aid_transcription
 * @property integer $resource_interpreter_guide
 * @property integer $resource_interpreter_libras
 * @property integer $resource_lip_reading
 * @property integer $resource_zoomed_test_16
 * @property integer $resource_zoomed_test_20
 * @property integer $resource_zoomed_test_24
 * @property integer $resource_zoomed_test_18
 * @property integer $resource_braille_test
 * @property integer $resource_proof_language
 * @property integer $resource_cd_audio
 * @property integer $resource_video_libras
 * @property integer $resource_additional_time
 * @property integer $resource_none
 * @property integer $send_year
 * @property string $last_change
 * @property integer $responsable
 * @property string $responsable_name
 * @property string $responsable_rg
 * @property string $responsable_cpf
 * @property string $responsable_nis
 * @property integer $responsable_scholarity
 * @property string $responsable_job
 * @property integer $bf_participator
 * @property string $responsable_telephone
 * @property string $responsable_email
 * @property string $tag_id
 * @property integer $no_documents_desc
 * @property string $fkid
 * @property integer $sedsp_sync
 * @property string $id_indigenous_people
 *
 * The followings are the available model relations:
 * @property StudentEnrollment[] $studentEnrollments
 * @property StudentEnrollment $lastEnrollment
 * @property EdcensoNation $edcensoNationFk
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 * @property SchoolIdentification $schoolInepIdFk
 * @property StudentDocumentsAndAddress $documentsFk
 */
class StudentIdentification extends AltActiveRecord
{
    public const CREATE = 'create';
    public const UPDATE = 'update';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return StudentIdentification the static model class
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
        return 'student_identification';
    }

    public function behaviors()
    {
        // Define os comportamentos padrão
        $behaviors = [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ]
        ];

        // Verifica se o usuário está associado a uma escola
        if (isset(Yii::app()->user->school)) {
            // Adiciona o comportamento CAfterSaveBehavior se a escola estiver definida
            $behaviors['CAfterSaveBehavior'] = [
                'class' => 'application.behaviors.CAfterSaveBehavior',
                'schoolInepId' => Yii::app()->user->school,
            ];
        }

        return $behaviors;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['school_inep_id_fk, birthday, sex, name, color_race, filiation, nationality, edcenso_nation_fk, deficiency, send_year', 'required'],
            ['edcenso_uf_fk, edcenso_city_fk', 'required', 'on' => 'formSubmit'],
            ['responsable_nis, sex, color_race, filiation, scholarity, nationality, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_monocular_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities, deficiency_type_autism, deficiency_type_aspenger_syndrome, deficiency_type_rett_syndrome, deficiency_type_childhood_disintegrative_disorder, deficiency_type_gifted, resource_aid_lector, resource_aid_transcription, resource_interpreter_guide, resource_interpreter_libras, resource_lip_reading, resource_zoomed_test_16, resource_zoomed_test_20, resource_zoomed_test_24, resource_zoomed_test_18, resource_braille_test, resource_proof_language, resource_cd_audio, resource_video_libras, resource_none, send_year, responsable, responsable_scholarity, filiation_1_scholarity, filiation_2_scholarity, bf_participator, no_document_desc', 'numerical', 'integerOnly' => true],
            ['register_type', 'length', 'max' => 2],
            ['school_inep_id_fk', 'length', 'max' => 8],
            ['inep_id', 'length', 'max' => 12],
            ['name, filiation_1, filiation_2', 'length', 'max' => 100],
            ['id_email, email_responsable', 'length', 'max' => 255],
            ['id_email', 'email'],
            ['birthday, filiation_1_birthday, filiation_2_birthday', 'length', 'max' => 10],
            ['responsable_name', 'length', 'max' => 90],
            ['responsable_rg, responsable_job, filiation_1_rg, filiation_2_rg, filiation_1_job, filiation_2_job', 'length', 'max' => 45],
            ['responsable_cpf, responsable_telephone, filiation_1_cpf, filiation_2_cpf', 'length', 'max' => 11],
            ['hash', 'length', 'max' => 40],
            ['last_change, civil_name', 'safe'],
            ['register_type, id_indigenous_people, school_inep_id_fk, inep_id, id, name, civil_name, birthday, sex, color_race, filiation, id_email, scholarity, filiation_1, filiation_2, nationality, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_monocular_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities, deficiency_type_autism, deficiency_type_aspenger_syndrome, deficiency_type_rett_syndrome, deficiency_type_childhood_disintegrative_disorder, deficiency_type_gifted, resource_aid_lector, resource_aid_transcription, resource_interpreter_guide, resource_interpreter_libras, resource_lip_reading, resource_zoomed_test_16, resource_zoomed_test_20, resource_zoomed_test_24, resource_zoomed_test_18, resource_braille_test, resource_proof_language, resource_cd_audio, resource_video_libras, resource_none, send_year, last_change, responsable, responsable_name, responsable_rg, responsable_cpf, responsable_scholarity, responsable_job, bf_participator, responsable_telephone, fkid, no_documents_desc', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'edcensoNationFk' => [self::BELONGS_TO, 'EdcensoNation', 'edcenso_nation_fk'],
            'studentDisorders' => [self::HAS_ONE, 'StudentDisorder', 'student_fk'],
            'edcensoUfFk' => [self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'],
            'edcensoCityFk' => [self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'],
            'schoolInepIdFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'],
            'documentsFk' => [self::BELONGS_TO, 'StudentDocumentsAndAddress', 'id'],
            'studentEnrollments' => [
                self::HAS_MANY,
                'StudentEnrollment',
                'student_fk',
                'with' => 'classroomFk',
                'together' => true,
                'order' => 'classroomFk.school_year DESC, status, studentEnrollments.id DESC'
            ],
            'lastEnrollment' => [
                self::HAS_ONE,
                'StudentEnrollment',
                'student_fk',
                'with' => 'classroomFk',
                'together' => true,
                'order' => 'classroomFk.school_year DESC, lastEnrollment.id DESC'
            ]
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'register_type' => Yii::t('default', 'Register Type'),
            'school_inep_id_fk' => Yii::t('default', 'School Inep Id Fk'),
            'inep_id' => Yii::t('default', 'ID INEP'),
            'gov_id' => Yii::t('default', 'GOV ID'),
            'id' => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Nome de Apresentação'),
            'civil_name' => Yii::t('default', 'Civil Name'),
            'social_name' => Yii::t('default', 'Social Name'),
            'birthday' => Yii::t('default', 'Birthday'),
            'sex' => Yii::t('default', 'Sex'),
            'color_race' => Yii::t('default', 'Color Race'),
            'filiation' => Yii::t('default', 'Filiation'),
            'id_email' => Yii::t('default', 'Id Email'),
            'scholarity' => Yii::t('default', 'Scholarity'),
            'filiation_1' => Yii::t('default', 'Mother Name'),
            'filiation_2' => Yii::t('default', 'Father Name'),
            'nationality' => Yii::t('default', 'Nationality'),
            'edcenso_nation_fk' => Yii::t('default', 'Edcenso Nation Fk'),
            'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
            'edcenso_city_fk' => Yii::t('default', 'Edcenso City Fk'),
            'deficiency' => Yii::t('default', 'Deficiency'),
            'deficiency_type_blindness' => Yii::t('default', 'Deficiency Type Blindness'),
            'deficiency_type_low_vision' => Yii::t('default', 'Deficiency Type Low Vision'),
            'deficiency_type_monocular_vision' => Yii::t('default', 'Deficiency Type Monocular Vision'),
            'deficiency_type_deafness' => Yii::t('default', 'Deficiency Type Deafness'),
            'deficiency_type_disability_hearing' => Yii::t('default', 'Deficiency Type Disability Hearing'),
            'deficiency_type_deafblindness' => Yii::t('default', 'Deficiency Type Deafblindness'),
            'deficiency_type_phisical_disability' => Yii::t('default', 'Deficiency Type Phisical Disability'),
            'deficiency_type_intelectual_disability' => Yii::t('default', 'Deficiency Type Intelectual Disability'),
            'deficiency_type_multiple_disabilities' => Yii::t('default', 'Deficiency Type Multiple Disabilities'),
            'deficiency_type_autism' => Yii::t('default', 'Deficiency Type Autism'),
            'deficiency_type_aspenger_syndrome' => Yii::t('default', 'Deficiency Type Aspenger Syndrome'),
            'deficiency_type_rett_syndrome' => Yii::t('default', 'Deficiency Type Rett Syndrome'),
            'deficiency_type_childhood_disintegrative_disorder' => Yii::t('default', 'Deficiency Type Childhood Disintegrative Disorder'),
            'deficiency_type_gifted' => Yii::t('default', 'Deficiency Type Gifted'),
            'resource_aid_lector' => Yii::t('default', 'Resource Aid Lector'),
            'resource_aid_transcription' => Yii::t('default', 'Resource Aid Transcription'),
            'resource_interpreter_guide' => Yii::t('default', 'Resource Interpreter Guide'),
            'resource_interpreter_libras' => Yii::t('default', 'Resource Interpreter Libras'),
            'resource_lip_reading' => Yii::t('default', 'Resource Lip Reading'),
            'resource_zoomed_test_16' => Yii::t('default', 'Resource Zoomed Test 16'),
            'resource_zoomed_test_20' => Yii::t('default', 'Resource Zoomed Test 20'),
            'resource_zoomed_test_24' => Yii::t('default', 'Resource Zoomed Test 24'),
            'resource_zoomed_test_18' => Yii::t('default', 'Resource Zoomed Test 18'),
            'resource_braille_test' => Yii::t('default', 'Resource Braille Test'),
            'resource_proof_language' => Yii::t('default', 'Resource Proof Language'),
            'resource_cd_audio' => Yii::t('default', 'Resource Cd Audio'),
            'resource_video_libras' => Yii::t('default', 'Resource Video Libras'),
            'resource_additional_time' => Yii::t('default', 'Resource Additional Time'),
            'resource_none' => Yii::t('default', 'Resource None'),
            'send_year' => Yii::t('default', 'Pós Censo'),
            'last_change' => Yii::t('default', 'Last Change'),
            'responsable' => Yii::t('default', 'Responsable'),
            'responsable_telephone' => Yii::t('default', "Responsible's Telephone"),
            'responsable_name' => Yii::t('default', 'Responsable`s Name'),
            'responsable_rg' => Yii::t('default', 'Responsable`s RG'),
            'responsable_cpf' => Yii::t('default', 'Responsable`s CPF'),
            'responsable_nis' => Yii::t('default', 'NIS do Responsável'),
            'responsable_scholarity' => Yii::t('default', 'Responsable`s Scholarity'),
            'responsable_job' => Yii::t('default', 'Responsable`s Job'),
            'bf_participator' => Yii::t('default', 'BF Participator'),
            'filiation_1_rg' => Yii::t('default', 'Mother RG'),
            'filiation_1_cpf' => Yii::t('default', 'Mother CPF'),
            'filiation_1_birthday' => Yii::t('default', 'Mother Birthday'),
            'filiation_1_scholarity' => Yii::t('default', 'Mother Scholarity'),
            'filiation_1_job' => Yii::t('default', 'Mother Job'),
            'filiation_2_rg' => Yii::t('default', 'Father RG'),
            'filiation_2_cpf' => Yii::t('default', 'Father CPF'),
            'filiation_2_birthday' => Yii::t('default', 'Father Birthday'),
            'filiation_2_scholarity' => Yii::t('default', 'Father Scholarity'),
            'filiation_2_job' => Yii::t('default', 'Father Job'),
            'no_document_desc' => Yii::t('default', 'No Documents Desc'),
            'id_indigenous_people' => Yii::t('default', 'Id Indigenous People')
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('register_type', $this->register_type, true);
        $criteria->compare('inep_id', $this->inep_id, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('filiation_1', $this->filiation_1, true);

        return new CActiveDataProvider(
            $this,
            [
                'criteria' => $criteria,
                'sort' => [
                    'defaultOrder' => [
                        'name' => CSort::SORT_ASC
                    ],
                ],
                'pagination' => false
            ]
        );
    }

    public function getConcatened()
    {
        return $this->name . ' (' . $this->filiation_1 . ')[' . $this->birthday . ']';
    }

    public function getCurrentStageVsModality()
    {
        $sid = isset($this->id) ? $this->id : 0;
        $sql = "select student_fk student, se.id enrollment, se.edcenso_stage_vs_modality_fk enrollment_svm, c.edcenso_stage_vs_modality_fk classroom_svm from student_enrollment se
                  join classroom c on c.id = se.classroom_fk
                where se.student_fk = $sid
                order by school_year desc;";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        $stage = null;
        if (isset($result)) {
            $stage = isset($result['enrollment_svm']) ? $result['enrollment_svm'] : $result['classroom_svm'];
        }
        return $stage;
    }

    public function syncStudentWithSED($id, $modelEnrollment, $type)
    {
        $studentInfo = $this->getStudentInformation($id);
        $studentIdentification = $studentInfo['studentIdentification'];
        $studentIdentification->sedsp_sync = 0;

        $studentIdentification->tag_to_sed = 1;
        $studentIdentification->save();

        $studentToSedMapper = new StudentMapper();
        $student = (object) $studentToSedMapper->parseToSEDAlunoFicha(
            $studentIdentification,
            $studentInfo['modelStudentDocumentsAndAddress']
        );

        $studentDatasource = new StudentSEDDataSource();

        $dataSource = new StudentSEDDataSource();
        $outListStudent = $dataSource->getListStudents($this->createInListarAlunos($studentIdentification->name, $studentIdentification->filiation_1, $studentIdentification->filiation_2));

        $return['identification'] = '';
        $return['enrollment'] = '';
        if (method_exists($outListStudent, 'getCode') && $this->handleUnauthorizedError($outListStudent->getCode())) {
            return false;
        }

        if ($type == self::CREATE || ($type == self::UPDATE && $outListStudent->outListaAlunos === null)) {
            if ($outListStudent->outErro !== null || !is_null($outListStudent)) {
                $inConsult = $this->createInConsult($student);
                $statusAdd = $dataSource->addStudentToSed($inConsult);

                if (method_exists($statusAdd, 'getCode') && $this->handleUnauthorizedError($statusAdd->getCode())) {
                    return false;
                }

                if ($statusAdd->outErro === null) {
                    $studentFromSed = StudentIdentification::model()->findByPk($id);
                    $studentFromSed->gov_id = $statusAdd->outAluno->outNumRA;
                    $studentFromSed->sedsp_sync = 1;

                    $studentFromSed->save();

                    if ($modelEnrollment->id !== null) {
                        $enrollmentResult = $this->processEnrollment($studentFromSed, $modelEnrollment);
                    }
                }
                $result['identification'] = $statusAdd;
                $result['enrollment'] = $enrollmentResult;
            }
        } elseif ($type == self::UPDATE) {
            $govId = $studentIdentification->gov_id === null ? $outListStudent->outListaAlunos[0]->getOutNumRa() : $studentIdentification->gov_id;

            $response = $studentDatasource->exibirFichaAluno(new InAluno($govId, null, 'SP'));
            if (method_exists($response, 'getCode') && $this->handleUnauthorizedError($response->getCode())) {
                return false;
            }

            $infoAluno = $response->outDadosPessoais->getOutNomeAluno();
            $filiation1 = $response->outDadosPessoais->getOutNomeMae();
            $filiation2 = $response->outDadosPessoais->getOutNomePai();

            $inListarAlunos = $this->createInListarAlunos($infoAluno, $filiation1, $filiation2);
            $dataSource = new StudentSEDDataSource();
            $outListStudent = $dataSource->getListStudents($inListarAlunos);

            if ($outListStudent->outErro === null) {
                $studentIdentification->gov_id = $govId;
                $studentIdentification->save();

                $dataSource = new StudentSEDDataSource();
                $student->InAluno->setInNumRA($govId);
                $inManutencao = $this->createInManutencao($student);
                $statusAdd = $dataSource->editStudent($inManutencao);

                if ($statusAdd->outErro === null) {
                    $studentIdentification->sedsp_sync = 1;
                    $studentIdentification->save();
                }
            }

            if ($modelEnrollment->id !== null) {
                $enrollmentResult = $this->processEnrollment($studentIdentification, $modelEnrollment);
            }

            $result['identification'] = $statusAdd;
            $result['enrollment'] = $enrollmentResult;
        }

        return $result;
    }

    public function processEnrollment($modelStudentIdentification, $modelEnrollment)
    {
        $enrollmentsSEDSP = StudentMapper::getListMatriculasRa($modelStudentIdentification->gov_id);
        $classroom = $modelEnrollment->classroomFk;
        $hasEnrollmentSEDSP = false;
        foreach ($enrollmentsSEDSP as $enrollmentSEDSP) {
            if ($enrollmentSEDSP->getOutNumClasse() == $classroom->gov_id) {
                $hasEnrollmentSEDSP = true;
                break;
            }
        }
        if (!$hasEnrollmentSEDSP) {
            $inAluno = new InAluno($modelStudentIdentification->gov_id, null, 'SP');
            $inAnoLetivo = Yii::app()->user->year;
            $inCodEscola = substr($modelStudentIdentification->school_inep_id_fk, 2);
            $inscricao = new InInscricao($inAnoLetivo, $inCodEscola, null, '4');

            $classroomMapper = new ClassroomMapper();
            $ensino = (object) $classroomMapper->convertStageToTipoEnsino($classroom->edcenso_stage_vs_modality_fk);
            $inNivelEnsino = new InNivelEnsino($ensino->tipoEnsino, $ensino->serieAno);

            $this->createEnrollStudent($inAluno, $inscricao, $inNivelEnsino);
            return $this->addEnrollmentToSedsp($modelStudentIdentification, $modelEnrollment);
        } else {
            $modelEnrollment->sedsp_sync = 1;
            $modelEnrollment->save();
        }
    }

    private function createEnrollStudent(InAluno $inAluno, InInscricao $inscricao, InNivelEnsino $inNivelEnsino)
    {
        //InscreverStudent
        $enrollStudent = new InscreverAluno($inAluno, $inscricao, $inNivelEnsino);
        $enrollStudentUseCase = new EnrollStudentUseCase();
        return $enrollStudentUseCase->exec($enrollStudent);
    }

    public function addEnrollmentToSedsp($modelStudentIdentification, $modelEnrollment)
    {
        $modelEnrollment->sedsp_sync = 0;
        $modelEnrollment->save();

        $enrollmentMapper = new EnrollmentMapper();
        $mapper = (object) $enrollmentMapper->parseToSEDEnrollment($modelStudentIdentification, $modelEnrollment);

        $addEnrollmentToSed = new AddMatriculaToSEDUseCase();
        $statusAddEnrollmentToSed = $addEnrollmentToSed->exec($mapper->Enrollment);

        if ($statusAddEnrollmentToSed->outErro === null) {
            $modelEnrollment->sedsp_sync = 1;
            $modelEnrollment->save();
        }
        return $statusAddEnrollmentToSed;
    }

    public function handleUnauthorizedError($statusCode)
    {
        if ($statusCode === 401) {
            return true;
        }
    }

    public function createInListarAlunos($nameStudent, $nameFiliation1, $nameFiliation2)
    {
        return new InListarAlunos(new InFiltrosNomes($nameStudent, null, $nameFiliation1, $nameFiliation2), null, null);
    }

    // Função para criar objeto InConsult em caso de aluno não cadastrado
    /**
     * Summary of createInConsult
     * @param mixed $student
     * @return InFichaAluno
     */
    public function createInConsult($student)
    {
        return new InFichaAluno(
            $student->InDadosPessoais,
            $student->InDeficiencia,
            $student->InRecursoAvaliacao,
            $student->InDocumentos,
            null,
            null,
            $student->InEnderecoResidencial,
            null
        );
    }

    // Função para criar objeto InManutencao em caso de aluno cadastrado
    /**
     * Summary of createInManutencao
     * @param mixed $student
     * @return InManutencao
     */
    public function createInManutencao($student)
    {
        return new InManutencao(
            $student->InAluno,
            $student->InDadosPessoais,
            $student->InDeficiencia,
            $student->InRecursoAvaliacao,
            $student->InDocumentos,
            null,
            null,
            $student->InEnderecoResidencial,
            null,
            null
        );
    }

    // Função para obter informações do aluno
    public function getStudentInformation($id)
    {
        $studentIdentification = StudentIdentification::model()->findByPk($id);
        $modelStudentDocumentsAndAddress = StudentDocumentsAndAddress::model()->findByPk($id);

        return [
            'studentIdentification' => $studentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
        ];
    }
}
