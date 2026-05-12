<?php

class Import extends CFormModel
{
    public const INSTRUCTOR_TEACHING_DATA = 'INSTRUCTOR_TEACHING_DATA';
    public const INSTRUCTOR_IDENTIFICATION = 'INSTRUCTOR_IDENTIFICATION';
    public const STUDENT_IDENTIFICATION = 'STUDENT_IDENTIFICATION';
    public const STUDENT_DOCUMENT_AND_ADDRESS = 'STUDENT_DOCUMENT_AND_ADDRESS';

    public $registers;
    public $instructorOwnSystemCodes;
    public $instructorInepId;
    public $studentOwnSystemCodes;
    public $studentInepId;
    public $year;
    public $file;
    public $importWithError;
    public $probable;

    public function __construct($scenario = '')
    {
        parent::__construct($scenario);
        $this->registers = [];
    }

    public function attributeNames()
    {
        return ['registers', 'year', 'file', 'importWithError', 'probable'];
    }

    public function attributeLabels()
    {
        return [
            'registers' => 'Registros',
            'year' => 'Ano do arquivo',
            'file' => 'Arquivo',
            'importWithError' => 'Importar com erro?',
            'probable' => 'Resultado provável?'
        ];
    }

    private function emptyRegisters()
    {
        return array_fill_keys(['00', '10', '20', '30', '40', '50', '60'], []);
    }

    private function hasValue($value)
    {
        return $value !== null && trim((string) $value) !== '';
    }

    private function findClassroom($schoolInepId, $year, $ownSystemCode, $inepId, $name = null)
    {
        if ($this->hasValue($inepId)) {
            $classroom = Classroom::model()->find(
                'school_inep_fk = :school_inep_fk and school_year = :year and inep_id = :inep_id',
                [
                    ':school_inep_fk' => $schoolInepId,
                    ':year' => $year,
                    ':inep_id' => $inepId,
                ]
            );

            if ($classroom !== null) {
                return $classroom;
            }
        }

        if ($this->hasValue($ownSystemCode)) {
            $classroom = Classroom::model()->find(
                'school_inep_fk = :school_inep_fk and school_year = :year and censo_own_system_code = :censo_own_system_code',
                [
                    ':school_inep_fk' => $schoolInepId,
                    ':year' => $year,
                    ':censo_own_system_code' => $ownSystemCode,
                ]
            );

            if ($classroom !== null) {
                return $classroom;
            }
        }

        if ($this->hasValue($name)) {
            return Classroom::model()->find(
                'school_inep_fk = :school_inep_fk and school_year = :year and name = :name',
                [
                    ':school_inep_fk' => $schoolInepId,
                    ':year' => $year,
                    ':name' => $name,
                ]
            );
        }

        return null;
    }

    private function findInstructor($schoolInepId, $ownSystemCode, $inepId)
    {
        if ($this->hasValue($inepId)) {
            $instructor = InstructorIdentification::model()->find(
                "inep_id is not null and inep_id != '' and inep_id = :inep_id",
                [':inep_id' => $inepId]
            );

            if ($instructor !== null) {
                return $instructor;
            }
        }

        if ($this->hasValue($ownSystemCode)) {
            return InstructorIdentification::model()->findByAttributes([
                'school_inep_id_fk' => $schoolInepId,
                'censo_own_system_code' => $ownSystemCode,
            ]);
        }

        return null;
    }

    public function run()
    {
        set_time_limit(0);
        ignore_user_abort();

        $file = fopen($this->file, 'r');
        if ($file === false) {
            $this->addError('file', 'O arquivo não existe.');
            return;
        }

        $registers = $this->emptyRegisters();
        $instructorOwnSystemCodes = [];
        $instructorInepId = [];
        $studentOwnSystemCodes = [];
        $studentInepId = [];

        while (true) {
            $line = fgets($file);
            if ($line == null) {
                break;
            }

            $registerType = substr(trim($line), 0, 2);
            if ($registerType === '') {
                continue;
            }

            $fields = explode('|', $line);
            $fields = array_map('trim', $fields);

            if (in_array($registerType, ['40', '50'], true)) {
                if ($this->hasValue($fields[2] ?? null)) {
                    array_push($instructorOwnSystemCodes, $fields[2]);
                }
                if ($this->hasValue($fields[3] ?? null)) {
                    array_push($instructorInepId, $fields[3]);
                }
            }
            if ($registerType === '60') {
                if ($this->hasValue($fields[2] ?? null)) {
                    array_push($studentOwnSystemCodes, $fields[2]);
                }
                if ($this->hasValue($fields[3] ?? null)) {
                    array_push($studentInepId, $fields[3]);
                }
            }
            $registers[$registerType][] = $fields;
        }

        fclose($file);

        $this->instructorOwnSystemCodes = array_unique($instructorOwnSystemCodes);
        $this->instructorInepId = array_unique($instructorInepId);
        $this->studentOwnSystemCodes = array_unique($studentOwnSystemCodes);
        $this->studentInepId = array_unique($studentInepId);
        $this->registers = $registers;
        $this->initImport($this->year);
    }

    private function initImport($year)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $this->importRegister00($this->registers['00'] ?? [], $year);
            $this->importRegister10($this->registers['10'] ?? [], $year);
            $this->importRegister20($this->registers['20'] ?? [], $year);
            $this->importRegister30($this->registers['30'] ?? [], $year);
            $this->importRegister40($this->registers['40'] ?? [], $year);
            $this->importRegister50($this->registers['50'] ?? [], $year);
            $this->importRegister60($this->registers['60'] ?? [], $year);

            if (!$this->hasErrors() || $this->importWithError) {
                $transaction->commit();
                Yii::app()->user->setFlash('success', 'Importação realizada com sucesso!');
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->addError('file', $e->getMessage());
        }
    }

    public function importRegister00($lines, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 0, 'year' => $year]);
        $schoolIdentification = new SchoolIdentification();
        $attributes = $schoolIdentification->attributeNames();

        foreach ($lines as $line) {
            $schoolIdentificationModel = SchoolIdentification::model()->find('inep_id = :inep_id', ['inep_id' => $line[1]]);
            if ($schoolIdentificationModel == null) {
                $schoolIdentificationModel = new SchoolIdentification();
            }
            $schoolIdentificationModel->setScenario(SchoolIdentification::SCENARIO_IMPORT);

            foreach ($fields as $field) {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;

                if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $attributes)) {
                    $schoolIdentificationModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }

            // Censo 2025: o campo 'regulation' pode vir ausente no arquivo.
            // Garante um valor padrão para não falhar a validação do modelo.
            if (empty($schoolIdentificationModel->regulation)) {
                $schoolIdentificationModel->regulation = $schoolIdentificationModel->regulation ?? 2;
            }

            $edcensoCityId = $line[7] ?? null;
            $edcensoCity = EdcensoCity::model()->findByPk($edcensoCityId);
            $edcensoDistrict = EdcensoDistrict::model()->findByAttributes(['edcenso_city_fk' => $edcensoCityId]);
            if ($edcensoCity === null || $edcensoDistrict === null) {
                $this->setFailure('00', $line, 'Cidade ou distrito não encontrado.');
                continue;
            }
            $schoolIdentificationModel->edcenso_uf_fk = $edcensoCity->edcenso_uf_fk;
            $schoolIdentificationModel->edcenso_district_fk = $edcensoDistrict->id;
            $schoolIdentificationModel->id_difflocation = $schoolIdentificationModel->id_difflocation == '' ? 7 : $schoolIdentificationModel->id_difflocation;
            if (!$schoolIdentificationModel->save()) {
                $this->setFailure('00', $line, TagUtils::stringfyValidationErrors($schoolIdentificationModel));
            }
        }
    }

    public function importRegister10($lines, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 10, 'year' => $year]);
        $schoolStructure = new SchoolStructure();
        $attributes = $schoolStructure->attributeNames();

        foreach ($lines as $line) {
            $schoolStructureModel = SchoolStructure::model()->find('school_inep_id_fk = :school_inep_id_fk', ['school_inep_id_fk' => $line[1]]);
            if ($schoolStructureModel == null) {
                $schoolStructureModel = new SchoolStructure();
            }

            foreach ($fields as $field) {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;

                if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $attributes)) {
                    $schoolStructureModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }

            if (!$schoolStructureModel->save()) {
                $this->setFailure('10', $line, TagUtils::stringfyValidationErrors($schoolStructureModel));
            }
        }
    }

    public function importRegister20($lines, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 20, 'year' => $year]);
        $classroom = new Classroom();
        $attributes = $classroom->attributeNames();

        foreach ($lines as $line) {
            $classroomModel = $this->findClassroom($line[1], $year, $line[2] ?? null, $line[3] ?? null, $line[4] ?? null);
            if ($classroomModel == null) {
                $classroomModel = new Classroom();
            }
            // Usa scenario de import para permitir o formato 2025 dos dias da semana
            $classroomModel->setScenario(Classroom::SCENARIO_IMPORT);

            foreach ($fields as $field) {
                if ($field->attr !== 'id') {
                    $columnName = $field->attr;
                    $collumnOrder = $field->corder - 1;

                    if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $attributes)) {
                        $classroomModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                    }
                }
            }

            // Censo 2025: os campos week_days_* passaram a carregar o horário no formato
            // "HH:MM-HH:MM" (ex: "07:20-11:45") ao invés de 0/1.
            // Converte para o formato interno do banco (inteiro 0/1) e extrai hora/minuto.
            $weekDayFields = [
                'week_days_sunday', 'week_days_monday', 'week_days_tuesday',
                'week_days_wednesday', 'week_days_thursday', 'week_days_friday', 'week_days_saturday',
            ];
            $timeRegex = '/^(\d{2}):(\d{2})-(\d{2}):(\d{2})$/';
            $parsedInitialHour = null;
            $parsedInitialMinute = null;
            $parsedFinalHour = null;
            $parsedFinalMinute = null;

            foreach ($weekDayFields as $dayField) {
                $val = $classroomModel->{$dayField} ?? '';
                if (preg_match($timeRegex, trim((string) $val), $matches)) {
                    // Formato 2025: "HH:MM-HH:MM" — extrai hora do primeiro dia com horário
                    if ($parsedInitialHour === null) {
                        $parsedInitialHour = $matches[1];
                        $parsedInitialMinute = $matches[2];
                        $parsedFinalHour = $matches[3];
                        $parsedFinalMinute = $matches[4];
                    }
                    $classroomModel->{$dayField} = 1;
                } elseif (trim((string) $val) === '' || $val === '0') {
                    $classroomModel->{$dayField} = 0;
                }
            }

            // Popula os campos de hora separados a partir do horário extraído dos dias
            if ($parsedInitialHour !== null) {
                $classroomModel->initial_hour = $parsedInitialHour;
                $classroomModel->initial_minute = $parsedInitialMinute;
                $classroomModel->final_hour = $parsedFinalHour;
                $classroomModel->final_minute = $parsedFinalMinute;
            } elseif (empty($classroomModel->initial_hour)) {
                // Turma não-presencial ou sem horário informado no arquivo 2025:
                // preenche com '00' para satisfazer a regra 'required' do modelo.
                $classroomModel->initial_hour = '00';
                $classroomModel->initial_minute = '00';
                $classroomModel->final_hour = '00';
                $classroomModel->final_minute = '00';
            }

            $classroomModel->assistance_type = 0;
            $classroomModel->school_year = $year;
            if ($this->hasValue($line[2] ?? null)) {
                $classroomModel->censo_own_system_code = $line[2];
            }
            // Garante que o inep_id da turma seja sempre gravado a partir do arquivo,
            // mesmo em turmas já existentes no banco com inep_id = NULL.
            if ($this->hasValue($line[3] ?? null)) {
                $classroomModel->inep_id = $line[3];
            }
            if (!$classroomModel->save()) {
                $debugInfo = 'DEBUG name=' . var_export($classroomModel->name, true)
                    . ' school_inep_fk=' . var_export($classroomModel->school_inep_fk, true)
                    . ' pedagogical_mediation_type=' . var_export($classroomModel->pedagogical_mediation_type, true)
                    . ' edcenso_stage_vs_modality_fk=' . var_export($classroomModel->edcenso_stage_vs_modality_fk, true)
                    . ' isNewRecord=' . var_export($classroomModel->isNewRecord, true)
                    . ' fieldsCount=' . count($fields);
                $this->setFailure('20', $line, TagUtils::stringfyValidationErrors($classroomModel) . ' | ' . $debugInfo);
            }
        }
    }

    public function importRegister30($lines, $year)
    {
        foreach ($lines as $line) {
            $isInstructor = (in_array($line[2], $this->instructorOwnSystemCodes) || in_array($line[3], $this->instructorInepId));

            if ($isInstructor) {
                $this->importRegister302($line, $year);
                // Pessoa que é professora em uma turma e aluna em outra: salvar também
                // como aluno para que o registro 60 consiga criar a matrícula.
                $alsoStudent = (in_array($line[2], $this->studentOwnSystemCodes) || in_array($line[3], $this->studentInepId));
                if ($alsoStudent) {
                    $this->importRegister301($line, $year);
                }
            } else {
                $this->importRegister301($line, $year);
            }
        }
    }

    public function importRegister301($line, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 301, 'year' => $year]);

        $studentDocumentModel = null;
        $studentIdentificationModel = null;

        if ($this->hasValue($line[3] ?? null)) {
            $studentIdentificationModel = StudentIdentification::model()->find("inep_id is not null and inep_id != '' and inep_id = :inep_id", ['inep_id' => $line[3]]);
        }
        if ($studentIdentificationModel == null) {
            if ($this->hasValue($line[4] ?? null)) {
                $studentDocumentModel = StudentDocumentsAndAddress::model()->find("cpf is not null and cpf != '' and cpf = :cpf", ['cpf' => $line[4]]);
            }
            if ($studentDocumentModel == null) {
                $studentIdentificationModel = StudentIdentification::model()->find('name = :name and birthday = :birthday', ['name' => $line[5], 'birthday' => $line[6]]);
            }
        }
        if ($studentIdentificationModel !== null) {
            $studentDocumentModel = StudentDocumentsAndAddress::model()->find('id = :id', ['id' => $studentIdentificationModel->id]);
            if ($studentDocumentModel === null) {
                $studentDocumentModel = new StudentDocumentsAndAddress();
            }
        } elseif ($studentDocumentModel !== null) {
            $studentIdentificationModel = StudentIdentification::model()->find('id = :id', ['id' => $studentDocumentModel->id]);
            if ($studentIdentificationModel === null) {
                $this->setFailure('30', $line, 'Documento de aluno encontrado sem identificação correspondente.');
                return;
            }
        } else {
            $studentIdentificationModel = new StudentIdentification();
            $studentDocumentModel = new StudentDocumentsAndAddress();
        }
        foreach ($fields as $field) {
            if ($field->attr !== 'id') {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;
                $modelType = $field->stable;

                if (is_null($modelType)) {
                    continue;
                }

                $model = $modelType == self::STUDENT_IDENTIFICATION ? $studentIdentificationModel : $studentDocumentModel;

                if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $model->attributeNames())) {
                    $model->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }
        }

        if ($this->hasValue($line[2] ?? null)) {
            $studentIdentificationModel->censo_own_system_code = $line[2];
        }
        $studentIdentificationModel->send_year = $this->year;
        $studentDocumentModel->school_inep_id_fk = $studentIdentificationModel->school_inep_id_fk;
        $studentDocumentModel->residence_zone = $studentDocumentModel->residence_zone == '' ? 1 : $studentDocumentModel->residence_zone;
        $studentDocumentModel->setScenario('censoimport');
        if ($studentIdentificationModel->validate() && $studentDocumentModel->validate()) {
            if ($studentIdentificationModel->save(false)) {
                $studentDocumentModel->student_fk = $studentIdentificationModel->inep_id;
                $studentDocumentModel->id = $studentIdentificationModel->id;
                if ($studentDocumentModel->save()) {
                    return;
                }
            }
        }

        $errorMsg = implode(' | ', array_filter([
            TagUtils::stringfyValidationErrors($studentIdentificationModel),
            TagUtils::stringfyValidationErrors($studentDocumentModel),
        ]));
        $this->setFailure('30', $line, $errorMsg ?: 'Falha ao salvar aluno (erro desconhecido).');
    }

    public function importRegister302($line, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 302, 'year' => $year]);

        $instructorDocumentModel = null;
        $instructorIdentificationModel = null;

        if ($this->hasValue($line[3] ?? null)) {
            $instructorIdentificationModel = InstructorIdentification::model()->find("inep_id is not null and inep_id != '' and inep_id = :inep_id", ['inep_id' => $line[3]]);
        }
        if ($instructorIdentificationModel == null) {
            if ($this->hasValue($line[4] ?? null)) {
                $instructorDocumentModel = InstructorDocumentsAndAddress::model()->find("cpf is not null and cpf != '' and cpf = :cpf", ['cpf' => $line[4]]);
            }
            if ($instructorDocumentModel == null) {
                $instructorIdentificationModel = InstructorIdentification::model()->find('name = :name and birthday_date = :birthday_date', ['name' => $line[5], 'birthday_date' => $line[6]]);
            }
        }
        if ($instructorIdentificationModel !== null) {
            $instructorDocumentModel = InstructorDocumentsAndAddress::model()->find('id = :id', ['id' => $instructorIdentificationModel->id]);
            if ($instructorDocumentModel === null) {
                $instructorDocumentModel = new InstructorDocumentsAndAddress(InstructorDocumentsAndAddress::SCENARIO_IMPORT);
            }
        } elseif ($instructorDocumentModel !== null) {
            $instructorIdentificationModel = InstructorIdentification::model()->find('id = :id', ['id' => $instructorDocumentModel->id]);
            if ($instructorIdentificationModel === null) {
                $this->setFailure('30', $line, 'Documento de professor encontrado sem identificação correspondente.');
                return;
            }
        } else {
            $instructorIdentificationModel = new InstructorIdentification(InstructorIdentification::SCENARIO_IMPORT);
            $instructorDocumentModel = new InstructorDocumentsAndAddress(InstructorDocumentsAndAddress::SCENARIO_IMPORT);
        }

        foreach ($fields as $field) {
            if ($field->attr !== 'id') {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;
                $modelType = $field->stable;

                if (is_null($modelType)) {
                    continue;
                }

                $model = $modelType == self::INSTRUCTOR_IDENTIFICATION ? $instructorIdentificationModel : $instructorDocumentModel;

                if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $model->attributeNames())) {
                    $model->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }
        }

        if ($this->hasValue($line[2] ?? null)) {
            $instructorIdentificationModel->censo_own_system_code = $line[2];
        }
        // Garante que o inep_id do professor seja sempre gravado a partir do arquivo,
        // mesmo em professores já existentes no banco com inep_id = NULL.
        if ($this->hasValue($line[3] ?? null)) {
            $instructorIdentificationModel->inep_id = $line[3];
        }
        $instructorDocumentModel->school_inep_id_fk = $instructorIdentificationModel->school_inep_id_fk;
        if ($instructorIdentificationModel->validate() && $instructorDocumentModel->validate()) {
            if ($instructorIdentificationModel->save(false)) {
                $instructorDocumentModel->id = $instructorIdentificationModel->id;
                if ($instructorDocumentModel->save()) {
                    return;
                }
            }
        }

        // Censo 2025: se o único erro de validação for CPF duplicado no documento,
        // significa que o professor já existe no banco (cadastro anterior pelo CPF).
        // Não é necessário criar novamente — ignora silenciosamente sem gerar falha.
        $identificationErrors = $instructorIdentificationModel->getErrors();
        $documentErrors = $instructorDocumentModel->getErrors();
        $onlyCpfDuplicate = empty($identificationErrors)
            && array_keys($documentErrors) === ['cpf'];
        if ($onlyCpfDuplicate) {
            // Professor já existe com CPF duplicado — garantir que inep_id e
            // censo_own_system_code do arquivo sejam gravados no registro existente,
            // pois o save() principal falhou antes de persistir esses valores.
            $existingDoc = InstructorDocumentsAndAddress::model()->find(
                "cpf is not null and cpf != '' and cpf = :cpf",
                [':cpf' => $instructorDocumentModel->cpf]
            );
            if ($existingDoc !== null) {
                $existing = InstructorIdentification::model()->findByPk($existingDoc->id);
                if ($existing !== null) {
                    $changed = false;
                    if ($this->hasValue($line[2] ?? null) && empty($existing->censo_own_system_code)) {
                        $existing->censo_own_system_code = $line[2];
                        $changed = true;
                    }
                    if ($this->hasValue($line[3] ?? null) && empty($existing->inep_id)) {
                        $existing->inep_id = $line[3];
                        $changed = true;
                    }
                    if ($changed) {
                        $existing->save(false);
                    }
                }
            }
            return;
        }

        $errorMsg = implode(' | ', array_filter([
            TagUtils::stringfyValidationErrors($instructorIdentificationModel),
            TagUtils::stringfyValidationErrors($instructorDocumentModel),
        ]));
        $this->setFailure('30', $line, $errorMsg ?: 'Falha ao salvar professor (erro desconhecido).');
    }

    public function importRegister40($lines, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 40, 'year' => $year]);
        $school = new SchoolIdentification();
        $attributes = $school->attributeNames();
        $attributes = array_diff($attributes, ['register_type', 'inep_id']);

        foreach ($lines as $line) {
            $schoolInepId = $line[1];

            if (!$this->hasValue($schoolInepId)) {
                continue;
            }

            $schoolModel = SchoolIdentification::model()->findByAttributes(['inep_id' => $schoolInepId]);
            if ($schoolModel === null) {
                $this->setFailure('40', $line, 'Escola não encontrada para atualizar dados do gestor.');
                continue;
            }

            foreach ($fields as $field) {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;

                if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $attributes)) {
                    $schoolModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }

            $inepId = $line[3] ?? null;
            if ($this->hasValue($inepId)) {
                $manager = Yii::app()->db->createCommand(
                    [
                        'select' => ['name', 'email'],
                        'from' => 'instructor_identification',
                        'where' => 'inep_id=:inep_id',
                        'params' => [':inep_id' => $inepId],
                    ]
                )->queryRow();

                if (is_array($manager)) {
                    $schoolModel->manager_name = $manager['name'];
                    $schoolModel->manager_email = $manager['email'];
                }
            }

            $schoolModel->regulation = $schoolModel->regulation ?? 2;

            if (!$schoolModel->save()) {
                $schoolModel->validate();
                $this->setFailure('40', $line, TagUtils::stringfyValidationErrors($schoolModel));
            }
        }
    }

    public function importRegister50($lines, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 50, 'year' => $year]);
        $instructorTeaching = new InstructorTeachingData(InstructorTeachingData::SCENARIO_IMPORT);
        $attributes = $instructorTeaching->attributeNames();

        foreach ($lines as $line) {
            $classroom = $this->findClassroom($line[1], $year, $line[4] ?? null, $line[5] ?? null);
            $instructor = $this->findInstructor($line[1], $line[2] ?? null, $line[3] ?? null);
            if ($classroom === null && $instructor === null) {
                $this->setFailure('50', $line, 'Professor e turma não encontrados para criar vínculo (inep_professor=' . ($line[3] ?? '') . ', cod_professor=' . ($line[2] ?? '') . ', inep_turma=' . ($line[5] ?? '') . ', cod_turma=' . ($line[4] ?? '') . ').');
                continue;
            }
            if ($classroom === null) {
                $this->setFailure('50', $line, 'Turma não encontrada para criar vínculo (inep_turma=' . ($line[5] ?? '') . ', cod_turma=' . ($line[4] ?? '') . ').');
                continue;
            }
            if ($instructor === null) {
                $this->setFailure('50', $line, 'Professor não encontrado para criar vínculo (inep_professor=' . ($line[3] ?? '') . ', cod_professor=' . ($line[2] ?? '') . ').');
                continue;
            }

            $instructorTeachingModel = InstructorTeachingData::model()->find('instructor_fk = :instructor_fk and classroom_id_fk = :classroom_id_fk', ['instructor_fk' => $instructor->id, 'classroom_id_fk' => $classroom->id]);
            if ($instructorTeachingModel == null) {
                $instructorTeachingModel = new InstructorTeachingData(InstructorTeachingData::SCENARIO_IMPORT);
                $instructorTeachingModel->classroom_id_fk = $classroom->id;
                $instructorTeachingModel->instructor_fk = $instructor->id;
            }

            foreach ($fields as $field) {
                if ($field->attr !== 'instructor_fk' && $field->attr !== 'classroom_id_fk') {
                    $columnName = $field->attr;
                    $collumnOrder = $field->corder - 1;

                    if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $attributes)) {
                        $instructorTeachingModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                    }
                }
            }

            if (!$instructorTeachingModel->save()) {
                $instructorTeachingModel->validate();
                $this->setFailure('50', $line, TagUtils::stringfyValidationErrors($instructorTeachingModel));
            }
        }
    }

    public function importRegister60($lines, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(['register' => 60, 'year' => $year]);
        $studentEnrollment = new StudentEnrollment();
        $attributes = $studentEnrollment->attributeNames();

        foreach ($lines as $line) {
            $ownCode = $line[2] ?? null;
            $inepId = $line[3] ?? null;
            $classroomInepId = $line[5] ?? null;
            $classroom = $this->findClassroom($line[1], $year, $line[4] ?? null, $classroomInepId);
            $student = null;
            if ($this->hasValue($inepId)) {
                $student = StudentIdentification::model()->findByAttributes(['inep_id' => $inepId]);
            }
            if ($student === null && $this->hasValue($ownCode)) {
                $student = StudentIdentification::model()->findByAttributes(['censo_own_system_code' => $ownCode]);
            }
            if ($classroom === null || $student === null) {
                $this->setFailure('60', $line, 'Aluno ou turma não encontrado para criar matrícula (inep=' . ($inepId ?? '') . ', cod=' . ($ownCode ?? '') . ').');
                continue;
            }

            $studentEnrollmentModel = StudentEnrollment::model()->find('student_fk = :student_fk and classroom_fk = :classroom_fk', ['student_fk' => $student->id, 'classroom_fk' => $classroom->id]);
            if ($studentEnrollmentModel == null) {
                $studentEnrollmentModel = new StudentEnrollment();
                $studentEnrollmentModel->classroom_fk = $classroom->id;
                $studentEnrollmentModel->student_fk = $student->id;
            }

            foreach ($fields as $field) {
                if ($field->attr !== 'student_fk' && $field->attr !== 'classroom_fk') {
                    $columnName = $field->attr;
                    $collumnOrder = $field->corder - 1;

                    if (isset($line[$collumnOrder]) && $line[$collumnOrder] != '' && in_array($columnName, $attributes)) {
                        $studentEnrollmentModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                    }
                }
            }

            // Censo 2025: o valor de edcenso_stage_vs_modality_fk pode vir como "0" ou
            // código inexistente na tabela de referência. !empty() considera "0" como vazio,
            // o que deixava o valor passar e causava violação de FK. A verificação agora
            // usa cast para int: qualquer valor <= 0 é nullado antes do save.
            $stageId = (int) $studentEnrollmentModel->edcenso_stage_vs_modality_fk;
            if ($stageId <= 0) {
                $studentEnrollmentModel->edcenso_stage_vs_modality_fk = null;
            } else {
                $stageExists = EdcensoStageVsModality::model()->findByPk($stageId);
                if ($stageExists === null) {
                    $this->setFailure('60', $line, 'Etapa/modalidade inválida (edcenso_stage_vs_modality_fk=' . $stageId . ') não encontrada na tabela de referência. Campo será ignorado.');
                    $studentEnrollmentModel->edcenso_stage_vs_modality_fk = null;
                }
            }

            try {
                if (!$studentEnrollmentModel->save()) {
                    $studentEnrollmentModel->validate();
                    $this->setFailure('60', $line, TagUtils::stringfyValidationErrors($studentEnrollmentModel));
                }
            } catch (Exception $e) {
                $this->setFailure('60', $line, 'Erro ao salvar matrícula: ' . $e->getMessage());
            }
        }
    }

    public function setFailure($registerType, $data, $message = '')
    {
        $this->addError('file', 'Registro ' . $registerType . ': ' . implode('|', $data) . " \n " . $message);
    }
}
