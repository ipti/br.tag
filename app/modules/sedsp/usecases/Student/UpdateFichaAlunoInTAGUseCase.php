<?php

class UpdateFichaAlunoInTAGUseCase
{
    public function exec($inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);

        if (method_exists($response, "getOutErro") && $response->getOutErro() === "" && $response->getCode() === 401) {

            $student = StudentIdentification::model()->findByAttributes(array("gov_id"=> $inAluno->inNumRA));
            $student->sedsp_sync = 0;
            $student->save();

            return $response->getCode();
        }

        if (method_exists($response, "getCode") && $response->getCode() === 401) {
            $student = StudentIdentification::model()->findByAttributes(array("gov_id"=> $inAluno->inNumRA));
            $student->sedsp_sync = 0;
            $student->save();

            return $response->getCode();
        }

        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);
            $mapperStudentIdentification = $mapper->StudentIdentification;

            $studentIdentification = $this->createOrUpdateStudentIdentification($mapperStudentIdentification);
            $mapperStudentDocuments = $mapper->StudentDocumentsAndAddress;
            $studentId = StudentIdentification::model()->find('gov_id = :govId', [':govId' => $mapperStudentDocuments->gov_id])->id;

            $this->updateStudentDocsAndAddress($mapperStudentDocuments, $studentId);
            $statusUpdateEnrollment = $this->createOrUpdateStudentEnrollment($mapper->StudentEnrollment);

            if($statusUpdateEnrollment === false){
                return -1;
            } else {
                return $studentIdentification;
            }
        } catch (Exception $e) {
            $this->handleException($mapperStudentIdentification, $e);
            return $e->getCode();
        }
    }

    private function createOrUpdateStudentIdentification($studentIdentification)
    {
        $existingStudent = $this->findStudentByIdentification($studentIdentification->gov_id);

        if ($existingStudent) {
            $existingStudent->attributes = $studentIdentification->attributes;

            if ($existingStudent->validate() && $existingStudent->save()) {
                $existingStudent->sedsp_sync = 1;
                return $existingStudent->save();
            }

            return false;
        }

        return $this->createAndSaveStudentIdentification($studentIdentification);
    }

    private function updateStudentDocsAndAddress($docsAndAddress, $studentId)
    {
        $studentDocument = StudentDocumentsAndAddress::model()->findByAttributes(array("id" => $studentId));

        if($studentDocument === null) {
            return $this->createStudentDocumentsAndAddress($docsAndAddress, $studentId);
        } else {
            $studentDocument->attributes = $docsAndAddress->attributes;
            $studentDocument->student_fk = $studentId;
            $studentDocument->gov_id = $docsAndAddress->gov_id;

            return $studentDocument->save();
        }
    }

    public function createOrUpdateStudentEnrollment($studentEnrollments)
    {

        foreach($studentEnrollments as $studentEnrollment) {
            $enrollment = StudentEnrollment::model()->find(array(
                'condition' => 'school_inep_id_fk=:school_inep_id_fk AND student_fk=:student_fk AND classroom_fk=:classroom_fk',
                'params' => array(
                    ':school_inep_id_fk' => $studentEnrollment->school_inep_id_fk,
                    ':student_fk' => $studentEnrollment->student_fk,
                    ':classroom_fk' => $studentEnrollment->classroom_fk,
                ),
            ));

            if ($enrollment === null) {
                $newEnrollment = new StudentEnrollment();
                $newEnrollment->attributes = $studentEnrollment->attributes;
                $newEnrollment->sedsp_sync = 0;

                if($newEnrollment->classroom_fk === null){
                    return false;
                }else{
                    if($newEnrollment->validate() && $newEnrollment->save()) {
                        $newEnrollment->sedsp_sync = 1;
                    }
    
                    return $newEnrollment->save();
                }
            } else {
                $enrollment->attributes = $studentEnrollment->attributes;
                $enrollment->sedsp_sync = 0;

                if($enrollment->validate() && $enrollment->save()){
                    $enrollment->sedsp_sync = 1;
                }
                return $enrollment->save();
            }
        }
    }

    private function findStudentByIdentification($govId)
    {
        return StudentIdentification::model()->find('gov_id = :gov_id', [':gov_id' => $govId]);
    }

    private function handleException($studentIdentification, $e)
    {
        $aluno = $this->findStudentByIdentification($studentIdentification->gov_id);
        $aluno->attributes = $studentIdentification->attributes;
        $aluno->sedsp_sync = 0;
        $aluno->save();

        $log = new LogError();
        $log->salvarDadosEmArquivo($e->getMessage());
    }


    public function createStudentDocumentsAndAddress($attributes, $studentId)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes->getAttributes();
        $studentDocumentsAndAddress->edcenso_city_fk = $attributes->edcenso_city_fk;
        $studentDocumentsAndAddress->gov_id = $attributes->gov_id;
        $studentDocumentsAndAddress->id = $studentId;

        return $studentDocumentsAndAddress->save();
    }

    public function createAndSaveStudentIdentification($attributes)
    {
        $studentIdentification = new StudentIdentification();
        $studentIdentification->attributes = $attributes->getAttributes();
        $studentIdentification->gov_id = $attributes->gov_id;

        return $studentIdentification->save();
    }
}
