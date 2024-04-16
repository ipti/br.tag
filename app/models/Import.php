<?php


class Import extends CModel
{

    const INSTRUCTOR_TEACHING_DATA = 'INSTRUCTOR_TEACHING_DATA';
    const INSTRUCTOR_IDENTIFICATION = 'INSTRUCTOR_IDENTIFICATION';
    const STUDENT_IDENTIFICATION = 'STUDENT_IDENTIFICATION';
    const STUDENT_DOCUMENT_AND_ADDRESS = 'STUDENT_DOCUMENT_AND_ADDRESS';

    public $registers;
    public $instructorOwnSystemCodes;
    public $instructorInepId;
    public $year;
    public $file;
    public $importWithError;
    public $probable;

    public function __constructor($year)
    {
        parent::__constructor();
        $this->registers = [];
    }

    public function attributeNames()
    {
        return ['registers', 'year', 'file', 'importWithError', 'probable'];
    }

    public function attributeLabels()
    {
        return [
            'registers' => "Registros",
            'year' => "Ano do arquivo",
            'file' => "Arquivo",
            'importWithError' => "Importar com erro?",
            'probable' => "Resultado provável?"
        ];
    }

    public function run()
    {
        set_time_limit(0);
        ignore_user_abort();

        $file = fopen($this->file, 'r');
        if ($file == false) {
            $this->setError('file', 'O arquivo não existe.');
            return;
        }

        $registers = [];
        $instructorOwnSystemCodes = [];
        $instructorInepId = [];

        while (true) {
            $line = fgets($file);
            if ($line == null) {
                break;
            }

            $registerType = substr($line, 0, 2);
            $fields = explode('|', $line);
            $fields = array_map('trim', $fields);

            if (in_array($registerType, [40, 50])) {
                if($fields[2] !== ""){
                    array_push($instructorOwnSystemCodes, $fields[2]);
                }
                array_push($instructorInepId, $fields[3]);
            }
            $registers[$registerType][] = $fields;
        }

        $this->instructorOwnSystemCodes = $instructorOwnSystemCodes;
        $this->instructorInepId = $instructorInepId;
        $this->registers = $registers;
        $this->initImport($this->year);
    }

    private function initImport($year)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $this->importRegister00($this->registers['00'], $year);
            $this->importRegister10($this->registers['10'], $year);
            $this->importRegister20($this->registers['20'], $year);
            $this->importRegister30($this->registers['30'], $year);
            $this->importRegister40($this->registers['40'], $year);
            $this->importRegister50($this->registers['50'], $year);
            $this->importRegister60($this->registers['60'], $year);

            if (!$this->hasErrors() || $this->importWithError) {
                $transaction->commit();
                Yii::app()->user->setFlash("success", "Importação realizada com sucesso!");
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

        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 0, 'year' => $year]);
        $schoolIdentification = new SchoolIdentification();
        $attributes = $schoolIdentification->attributeNames();

        foreach ($lines as $line) {

            $schoolIdentificationModel = SchoolIdentification::model()->find("inep_id = :inep_id", ["inep_id" => $line[1]]);
            if ($schoolIdentificationModel == null) {
                $schoolIdentificationModel = new SchoolIdentification();
            }

            foreach ($fields as $field) {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;

                if (isset ($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)) {
                    $schoolIdentificationModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }

            $edcensoCityId = $line[7];
            $edcensoCity = EdcensoCity::model()->findByPk($edcensoCityId);
            $edcensoDistrict = EdcensoDistrict::model()->findByAttributes(['edcenso_city_fk' => $edcensoCityId]);
            $schoolIdentificationModel->edcenso_uf_fk = $edcensoCity->edcenso_uf_fk;
            $schoolIdentificationModel->edcenso_district_fk = $edcensoDistrict->id;
            $schoolIdentificationModel->id_difflocation = $schoolIdentificationModel->id_difflocation == "" ? 7 : $schoolIdentificationModel->id_difflocation;
            if (!$schoolIdentificationModel->save()) {
                $this->setFailure('00', $line);
            }

        }
    }

    public function importRegister10($lines, $year)
    {

        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 10, 'year' => $year]);
        $schoolStructure = new SchoolStructure();
        $attributes = $schoolStructure->attributeNames();

        foreach ($lines as $line) {

            $schoolStructureModel = SchoolStructure::model()->find("school_inep_id_fk = :school_inep_id_fk", ["school_inep_id_fk" => $line[1]]);
            if ($schoolStructureModel == null) {
                $schoolStructureModel = new SchoolStructure();
            }

            foreach ($fields as $field) {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;

                if (isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)) {
                    $schoolStructureModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }

            if (!$schoolStructureModel->save()) {
                $this->setFailure('10', $line);
            }

        }
    }

    public function importRegister20($lines, $year)
    {

        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 20, 'year' => $year]);
        $classroom = new Classroom();
        $attributes = $classroom->attributeNames();

        foreach ($lines as $line) {

            $classroomModel = Classroom::model()->find("school_inep_fk = :school_inep_fk and school_year = :year and name = :name", ["school_inep_fk" => $line[1], "year" => $year, "name" => $line[4]]);
            if ($classroomModel == null) {
                $classroomModel = new Classroom();
            }

            foreach ($fields as $field) {
                if ($field->attr !== "id") {
                    $columnName = $field->attr;
                    $collumnOrder = $field->corder - 1;

                    if (isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)) {
                        $classroomModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                    }
                }
            }

            $classroomModel->assistance_type = 0;
            $classroomModel->school_year = $this->year;
            $classroomModel->censo_own_system_code = $line[2];
            if (!$classroomModel->save()) {
                $this->setFailure('20', $line);
            }

        }
    }


    public function importRegister30($lines, $year)
    {

        foreach ($lines as $line) {
            $isStudent = !(in_array($line[2], $this->instructorOwnSystemCodes) || in_array($line[3], $this->instructorInepId));

            if ($isStudent) {
                $this->importRegister301($line, $year);
            } else {
                $this->importRegister302($line, $year);
            }
        }
    }


    public function importRegister301($line, $year)
    {

        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 301, 'year' => $year]);

        $studentIdentificationModel = StudentIdentification::model()->find("inep_id is not null and inep_id != '' and inep_id = :inep_id", ["inep_id" => $line[3]]);
        if ($studentIdentificationModel == null) {
            $studentDocumentModel = StudentDocumentsAndAddress::model()->find("cpf is not null and cpf != '' and cpf = :cpf", ["cpf" => $line[4]]);
            if ($studentDocumentModel == null) {
                $studentIdentificationModel = StudentIdentification::model()->find("name = :name and birthday = :birthday", ["name" => $line[5], "birthday" => $line[6]]);
            }
        }
        if ($studentIdentificationModel !== null) {
            $studentDocumentModel = StudentDocumentsAndAddress::model()->find("id = :id", ["id" => $studentIdentificationModel->id]);
        } else if ($studentDocumentModel !== null) {
            $studentIdentificationModel = StudentIdentification::model()->find("id = :id", ["id" => $studentDocumentModel->id]);
        } else {
            $studentIdentificationModel = new StudentIdentification();
            $studentDocumentModel = new StudentDocumentsAndAddress();
        }
        foreach ($fields as $field) {
            if ($field->attr !== "id") {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;
                $modelType = $field->stable;

                if (is_null($modelType)) {
                    continue;
                }

                $model = $modelType == self::STUDENT_IDENTIFICATION ? $studentIdentificationModel : $studentDocumentModel;

                if (isset ($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $model->attributeNames())) {
                    $model->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }
        }

        $studentIdentificationModel->censo_own_system_code = $line[2];
        $studentIdentificationModel->send_year = $this->year;
        $studentDocumentModel->school_inep_id_fk = $studentIdentificationModel->school_inep_id_fk;
        $studentDocumentModel->residence_zone = $studentDocumentModel->residence_zone == "" ? 1 : $studentDocumentModel->residence_zone;
        $studentDocumentModel->setScenario("censoimport");
        if ($studentIdentificationModel->validate() && $studentDocumentModel->validate()) {
            if ($studentIdentificationModel->save(false)) {
                $studentDocumentModel->student_fk = $studentIdentificationModel->inep_id;
                $studentDocumentModel->id = $studentIdentificationModel->id;
                $studentDocumentModel->save();
                return;
            }
        }

        $this->setFailure('30', $line);

    }

    public function importRegister302($line, $year)
    {
        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 302, 'year' => $year]);

        $instructorIdentificationModel = InstructorIdentification::model()->find("inep_id is not null and inep_id != '' and inep_id = :inep_id", ["inep_id" => $line[3]]);
        if ($instructorIdentificationModel == null) {
            $instructorDocumentModel = InstructorDocumentsAndAddress::model()->find("cpf is not null and cpf != '' and cpf = :cpf", ["cpf" => $line[4]]);
            if ($instructorDocumentModel == null) {
                $instructorIdentificationModel = InstructorIdentification::model()->find("name = :name and birthday_date = :birthday_date", ["name" => $line[5], "birthday_date" => $line[6]]);
            }
        }
        if ($instructorIdentificationModel !== null) {
            $instructorDocumentModel = InstructorDocumentsAndAddress::model()->find("id = :id", ["id" => $instructorIdentificationModel->id]);
        } else if ($instructorDocumentModel !== null) {
            $instructorIdentificationModel = InstructorIdentification::model()->find("id = :id", ["id" => $instructorDocumentModel->id]);
        } else {
            $instructorIdentificationModel = new InstructorIdentification(InstructorIdentification::SCENARIO_IMPORT);
            $instructorDocumentModel = new InstructorDocumentsAndAddress(InstructorDocumentsAndAddress::SCENARIO_IMPORT);
        }

        foreach ($fields as $field) {
            if ($field->attr !== "id") {
                $columnName = $field->attr;
                $collumnOrder = $field->corder - 1;
                $modelType = $field->stable;

                if (is_null($modelType)) {
                    continue;
                }

                $model = $modelType == self::INSTRUCTOR_IDENTIFICATION ? $instructorIdentificationModel : $instructorDocumentModel;

                if (isset ($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $model->attributeNames())) {
                    $model->{$columnName} = utf8_encode($line[$collumnOrder]);
                }
            }
        }

        $instructorIdentificationModel->censo_own_system_code = $line[2];
        $instructorDocumentModel->school_inep_id_fk = $instructorIdentificationModel->school_inep_id_fk;
        if ($instructorIdentificationModel->validate() && $instructorDocumentModel->validate()) {
            if ($instructorIdentificationModel->save(false)) {
                $instructorDocumentModel->id = $instructorIdentificationModel->id;
                $instructorDocumentModel->save();
                return;
            }
        }

        $this->setFailure('30', $line);

    }

    public function importRegister40($lines, $year)
    {

        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 40, 'year' => $year]);
        $school = new SchoolIdentification();
        $attributes = $school->attributeNames();
        $attributes = array_diff($attributes, ['register_type', 'inep_id']);

        foreach ($lines as $line) {

            $schoolInepId = $line[1];

            if (is_null($schoolInepId)) {
                continue;
            }

            $schoolModel = SchoolIdentification::model()->findByAttributes(['inep_id' => $schoolInepId]);

            if (!is_null($schoolModel)) {
                foreach ($fields as $field) {
                    $columnName = $field->attr;
                    $collumnOrder = $field->corder - 1;

                    if (isset ($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)) {
                        $schoolModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                    }
                }
            }

            $inepId = $line[3];
            if (isset ($inepId)) {

                $manager = Yii::app()->db->createCommand(
                    array(
                        'select' => array('name', 'email'),
                        'from' => 'instructor_identification',
                        'where' => 'inep_id=:inep_id',
                        'params' => array(':inep_id' => $inepId),
                    )
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
        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 50, 'year' => $year]);
        $instructorTeaching = new InstructorTeachingData(InstructorTeachingData::SCENARIO_IMPORT);
        $attributes = $instructorTeaching->attributeNames();

        foreach ($lines as $line) {


            $classroom = Classroom::model()->findByAttributes(['school_inep_fk' => $line[1], 'school_year' => $year, 'censo_own_system_code' => $line[4]]);
            $instructor = InstructorIdentification::model()->findByAttributes(['school_inep_id_fk' => $line[1], 'censo_own_system_code' => $line[2]]);
            $instructorTeachingModel = InstructorTeachingData::model()->find("instructor_fk = :instructor_fk and classroom_id_fk = :classroom_id_fk", ["instructor_fk" => $instructor->id, "classroom_id_fk" => $classroom->id]);
            if ($instructorTeachingModel == null) {
                $instructorTeachingModel = new InstructorTeachingData(InstructorTeachingData::SCENARIO_IMPORT);
                $instructorTeachingModel->classroom_id_fk = $classroom->id;
                $instructorTeachingModel->instructor_fk = $instructor->id;
            }

            foreach ($fields as $field) {
                if ($field->attr !== "instructor_fk" && $field->attr !== "classroom_id_fk") {
                    $columnName = $field->attr;
                    $collumnOrder = $field->corder - 1;

                    if (isset ($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)) {
                        $instructorTeachingModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                    }
                }
            }

            if (!$instructorTeachingModel->save()) {
                $this->setFailure('50', $line);
            }

        }
    }

    public function importRegister60($lines, $year)
    {

        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => 60, 'year' => $year]);
        $studentEnrollment = new StudentEnrollment();
        $attributes = $studentEnrollment->attributeNames();

        foreach ($lines as $line) {
            $inepId = $line[3];
            $classroomInepId = $line[5];
            // $classroom = Classroom::model()->findByAttributes(['school_inep_fk' => $line[1], 'school_year' => $year, 'censo_own_system_code' => $line[4]]);
            $classroom = Classroom::model()->findByAttributes(['inep_id' => $classroomInepId]);
            // $student = StudentIdentification::model()->findByAttributes(['school_inep_id_fk' => $line[1], 'censo_own_system_code' => $line[2]]);
            $student = StudentIdentification::model()->findByAttributes(['inep_id' => $inepId]);
            $studentEnrollmentModel = StudentEnrollment::model()->find("student_fk = :student_fk and classroom_fk = :classroom_fk", ["student_fk" => $student->id, "classroom_fk" => $classroom->id]);
            if ($studentEnrollmentModel == null) {
                $studentEnrollmentModel = new StudentEnrollment();
                $studentEnrollmentModel->classroom_fk = $classroom->id;
                $studentEnrollmentModel->student_fk = $student->id;
            }

            foreach ($fields as $field) {
                if ($field->attr !== "student_fk" && $field->attr !== "classroom_fk") {
                    $columnName = $field->attr;
                    $collumnOrder = $field->corder - 1;

                    if (isset ($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)) {
                        $studentEnrollmentModel->{$columnName} = utf8_encode($line[$collumnOrder]);
                    }
                }
            }

            if (!$studentEnrollmentModel->save()) {
                $studentEnrollmentModel->validate();
                $this->setFailure('60', $line, TagUtils::stringfyValidationErrors($studentEnrollmentModel));
            }
        }
    }

    public function setFailure($registerType, $data, $message = "")
    {
        $this->addError('file', implode("|", $data) . " \n " . $message);
    }
}


?>
