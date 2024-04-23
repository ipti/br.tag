<?php

class CreateStudentAction extends CAction
{

    const CREATE = 'create';
    public function run()
    {
        Yii::log("AAAAAAAAAAAAAAAAA", CLogger::LEVEL_INFO);
        //  Load data for view
        $student = new StudentIdentification;
        $student->deficiency = 0;
        $studentDocsAndAddrs = new StudentDocumentsAndAddress;
        $modelEnrollment = new StudentEnrollment;
        $studentRestrictions = new StudentRestrictions;

        $vaccines = $this->listAllVaccines();
        $studentVaccinesSaves = $this->getStudentVaccines($student->id);

        if (Yii::app()->request->isPostRequest) {
            $student->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $studentDocsAndAddrs->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $studentRestrictions->attributes = $_POST[$this->STUDENT_RESTRICTIONS];

            try {
                [$student, $studentDocsAndAddrs, $studentRestrictions, $modelEnrollment] = $this->createNewStudent($student, $studentDocsAndAddrs, $studentRestrictions);
                Yii::app()->user->setFlash("success", 'O Cadastro de ' . $student->name . ' foi criado com sucesso!');
                $this->redirect(array('index', 'sid' => $student->id));
            } catch (StudentValidationException $e) {
                Yii::app()->user->setFlash('error', $e->getMessage());
                $this->redirect(array('index'));
            } catch (Exception $e){
                Yii::error($e);
            }


        }

        $this->getController()->render(
            'create',
            array(
                'modelStudentIdentification' => $student,
                'modelStudentDocumentsAndAddress' => $studentDocsAndAddrs,
                'modelStudentRestrictions' => $studentRestrictions,
                'modelEnrollment' => $modelEnrollment,
                'vaccines' => $vaccines,
                'studentVaccinesSaves' => $studentVaccinesSaves
            )
        );
    }

    private function listAllVaccines()
    {
        return Vaccine::model()->findAll(array('order' => 'name'));
    }

    private function getStudentVaccines($studentId)
    {
        $studentVaccinesSaves = StudentVaccine::model()->findAll(
            [
                'select' => 'vaccine_id',
                'condition' => 'student_id=:student_id',
                'params' => [
                    ':student_id' => $studentId
                ]
            ]
        );

        if ($studentVaccinesSaves) {
            $studentVaccinesSaves = array_map(function ($item) {
                return $item->vaccine_id;
            }, $studentVaccinesSaves);
        }

        return $studentVaccinesSaves;
    }

    private function createNewStudent($student, $studentDocsAndAddr, $studentRestrictions)
    {

        if ($this->hasStudentWithCPFRegistred($studentDocsAndAddr->cpf)) {
            throw new StudentValidationException("O CPF informado já está cadastrado", 1);
        }

        if ($this->hasCivilCertification($studentDocsAndAddr->civil_certification_term_number)) {
            throw new StudentValidationException("O Nº do Termo da Certidão informado já está cadastrado", 1);
        }

        $this->createStudentIdentification($student, $studentDocsAndAddr);
        $this->createDocumentsAndAddress($student, $studentDocsAndAddr);
        $this->createStudentRestrictions($student->id, $studentRestrictions);

        if ($this->hasRequestEnrollment()) {
            $modelEnrollment = $this->createEnrollment($student->id, $student->school_inep_id_fk);
        }

        if ($this->hasStudentVaccines()) {
            $this->createStudentVaccines($student->id);
        }


        if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
            [$flash, $msg] = $this->syncNewStudentWithSEDSP($student, $modelEnrollment);
            Yii::app()->user->setFlash($flash, Yii::t('default', $msg));
        }

        Log::model()->saveAction(
            "student",
            $student->id,
            "C",
            $student->name
        );

        return [
            $student,
            $studentDocsAndAddr,
            $studentRestrictions
        ];
    }
    private function createStudentIdentification(&$student, &$studentDocsAndAddr)
    {

        if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
            $student->scenario = "formSubmit";
        }

        date_default_timezone_set("America/Recife");
        $student->last_change = date('Y-m-d G:i:s');
        if ($student->validate() && $studentDocsAndAddr->validate()) {
            return $student->save();
        }

        throw new StudentValidationException("Um ou mais dados fornecidos do aluno estão inválidos, verifique e tente novamente", 1);
    }

    private function createDocumentsAndAddress($student, &$studentDocsAndAddr): bool
    {
        //Atributos comuns entre as tabelas
        $studentDocsAndAddr->school_inep_id_fk = $student->school_inep_id_fk;
        $studentDocsAndAddr->student_fk = $student->inep_id;
        $studentDocsAndAddr->id = $student->id;
        if ($studentDocsAndAddr->validate()) {
            return $studentDocsAndAddr->save();
        }

        throw new StudentValidationException("Um ou mais dados fornecidos dos documentos aluno estão inválidos, verifique e tente novamente", 1);
    }
    private function createStudentRestrictions($studentId, $retrictionsData)
    {

        $retrictionsData->student_fk = $studentId;

        if ($retrictionsData->validate()) {
            return $retrictionsData->save();
        }

        throw new StudentValidationException("Um ou mais dados fornecidos dos restrições alimentares do aluno estão inválidos, verifique e tente novamente", 1);
    }

    private function createEnrollment($studentId, $schoolInep)
    {
        //@todo: remover post
        $modelEnrollment = new StudentEnrollment;
        $modelEnrollment->attributes = $_POST[$this->STUDENT_ENROLLMENT];
        $modelEnrollment->school_inep_id_fk = $studentId;
        $modelEnrollment->student_fk = $schoolInep;
        $modelEnrollment->create_date = date('Y-m-d');
        $modelEnrollment->daily_order = $modelEnrollment->getDailyOrder();

        if ($modelEnrollment->validate()) {
            $modelEnrollment->save();
            return $modelEnrollment;
        }

        throw new StudentValidationException("Um ou mais dados fornecidos da matrícula do aluno estão inválidos, verifique e tente novamente", 1);
    }

    private function createStudentVaccines($studentId)
    {
        StudentVaccine::model()->deleteAll("student_id = :studentId", [":studentId" => $studentId]);
        foreach ($_POST['Vaccine']['vaccine_id'] as $vaccineId) {
            $studentVaccine = new StudentVaccine();
            $studentVaccine->student_id = $studentId;
            $studentVaccine->vaccine_id = $vaccineId;
            $studentVaccine->save();
        }
    }

    private function syncNewStudentWithSEDSP($student, $modelEnrollment)
    {
        $this->authenticateSedToken();
        $syncResult = $student->syncStudentWithSED($student->id, $modelEnrollment, self::CREATE);
        $flash = "success";
        if ($syncResult->identification->outErro !== null || $syncResult->enrollment->outErro !== null || $syncResult === false) {
            $flash = "error";
            $msg = '<span style="color: white;background: #23b923; padding:10px;border-radius: 4px;">Cadastro do aluno ' . $student->name .
                '  criado com sucesso no TAG, mas não foi possível sincronizá-lo com a SEDSP. Motivo: </span>';
            if ($syncResult->identification->outErro) {
                $msg .= "<br>Ficha do Aluno: " . $syncResult->identification->outErro;
            }
            if ($syncResult->enrollment->outErro) {
                $msg .= "<br>Matrícula: " . $syncResult->enrollment->outErro;
            }
        }
        return [
            $flash,
            $msg
        ];
    }

    private function hasStudentVaccines()
    {
        return isset($_POST['Vaccine']['vaccine_id']) && count($_POST['Vaccine']['vaccine_id']) > 0;
    }

    private function hasRequestEnrollment()
    {
        return isset($_POST[$this->STUDENT_ENROLLMENT], $_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])
            && !empty($_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"]);
    }

    private function hasStudentWithCPFRegistred($cpf)
    {
        if ($cpf != null) {
            $studentTestCpf = StudentDocumentsAndAddress::model()->find('cpf=:cpf', array(':cpf' => $cpf));
            return isset($studentTestCpf);
        }

        return false;
    }

    private function hasCivilCertification($civilCertification)
    {
        if ($civilCertification != null) {
            $studentTestCert = StudentDocumentsAndAddress::model()->find('civil_certification_term_number=:civil_certification_term_number', array(':civil_certification_term_number' => $civilCertification));
            return isset($studentTestCert);
        }

        return false;
    }
}
