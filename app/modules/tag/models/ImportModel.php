<?php

class ImportModel
{
    function saveSchoolIdentificationsDB($jsonSchools) 
    {
        foreach ($jsonSchools as $school) {
            $schoolIdentificationModel = new SchoolIdentification();
            $schoolIdentificationModel->setDb2Connection(true);
            $schoolIdentificationModel->refreshMetaData();
            $schoolIdentificationModel->attributes = $school;
            $schoolIdentificationModel->save();        
        }
    }
    
    function saveSchoolStructureDB($schoolStructures) 
    {
        foreach ($schoolStructures as $schoolStructure) {
            $schoolStructureModel =  new SchoolStructure();
            $schoolStructureModel->setDb2Connection(true);
            $schoolStructureModel->refreshMetaData();
            $schoolStructureModel->attributes = $schoolStructure;
            $schoolStructureModel->save();
        }
    }

    function saveClassroomsDB($jsonClassrooms) {
        foreach ($jsonClassrooms as  $classroom) {
            $classroomModel = new Classroom();
            $classroomModel->setDb2Connection(true);
            $classroomModel->refreshMetaData();
            $classroomModel->attributes = $classroom;
            $classroomModel->id =  $classroom['id'];
            $classroomModel->save();
        }
    }

    function saveStudentDocumentsAndAddressDB($jsonDocumentsAddress) {
        foreach ($jsonDocumentsAddress as $documentsaddress) {
            $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
            $studentDocumentsAndAddress->setDb2Connection(true);
            $studentDocumentsAndAddress->refreshMetaData();
            $studentDocumentsAndAddress->attributes = $documentsaddress;
            $studentDocumentsAndAddress->id = $documentsaddress['id'];
            $studentDocumentsAndAddress->save();
        }
    }

    function saveStudentEnrollmentDB($jsonEnrollments)
    {
        foreach ($jsonEnrollments as $enrollment) {
            $studentEnrollment = new StudentEnrollment();
            $studentEnrollment->setDb2Connection(true);
            $studentEnrollment->refreshMetaData();
            $studentEnrollment->attributes = $enrollment;
            $studentEnrollment->id = $enrollment['id'];
            $studentEnrollment->save();
        }
    }

    function saveStudentIdentificationDB($studentIdentifications)
    {
        foreach ($studentIdentifications as $studentIdentification) {
            $studentIdentificationMode = new StudentIdentification();
            $studentIdentificationMode->setDb2Connection(true);
            $studentIdentificationMode->refreshMetaData();
            $studentIdentificationMode->attributes = $studentIdentification;
            $studentIdentificationMode->id = $studentIdentification['id'];
            $studentIdentificationMode->save();
        }
    }

    function saveInstructorsTeachingDataDB($jsonInstructorsTeachingDatas)
    {
        foreach ($jsonInstructorsTeachingDatas as $instructorsTeachingData) {
            $instructorTeachingData = new InstructorTeachingData();
            $instructorTeachingData->setDb2Connection(true);
            $instructorTeachingData->refreshMetaData();
            $instructorTeachingData->attributes = $instructorsTeachingData;
            $instructorTeachingData->id = $instructorsTeachingData['id'];
            $instructorTeachingData->save();
         }
    }

    function saveInstructorDataDB($instructorIdentificationDatas, $instructorDocumentsAndAddressDatas, $instructorVariableDatas)
    {
        $instructorIdentificationModels = [];
        $instructorDocumentsAndAddressModels = [];
    
        // Criar instâncias dos modelos e associar os dados corretos
        foreach ($instructorIdentificationDatas as $instructorIdentificationData) {
            $instructorIdentificationModel = new InstructorIdentification();
            $instructorIdentificationModel->setDb2Connection(true);
            $instructorIdentificationModel->refreshMetaData();
            $instructorIdentificationModel->attributes = $instructorIdentificationData;
            $instructorIdentificationModel->id = $instructorIdentificationData['id'];
    
            $instructorIdentificationModels[$instructorIdentificationData['id']] = $instructorIdentificationModel;

            // Salvar os dados da tabela instructor_variable_data no banco de dados
            foreach ($instructorVariableDatas as $instructorVariableData) {
                if($instructorIdentificationData['id'] == $instructorVariableData['id']) {
                    $instructorVariableDataModel = new InstructorVariableData();
                    $instructorVariableDataModel->setDb2Connection(true);
                    $instructorVariableDataModel->refreshMetaData();
                    $instructorVariableDataModel->attributes = $instructorVariableData;
                    $instructorVariableDataModel->id = $instructorVariableData['id'];
    
                    // Salvar o dado no banco de dados
                    $instructorVariableDataModel->save();
                }
            }
        }
    
        foreach ($instructorDocumentsAndAddressDatas as $instructorDocumentsAndAddressData) {
            $instructorDocumentsAndAddressModel = new InstructorDocumentsAndAddress();
            $instructorDocumentsAndAddressModel->setDb2Connection(true);
            $instructorDocumentsAndAddressModel->refreshMetaData();
            $instructorDocumentsAndAddressModel->attributes = $instructorDocumentsAndAddressData;
            $instructorDocumentsAndAddressModel->id = $instructorDocumentsAndAddressData['id'];
    
            $instructorDocumentsAndAddressModels[$instructorDocumentsAndAddressData['id']] = $instructorDocumentsAndAddressModel;
        }
    
        // Associar os modelos corretos e salvar no banco de dados
        foreach ($instructorIdentificationModels as $id => $instructorIdentificationModel) {
            if (isset($instructorDocumentsAndAddressModels[$id])) {
                $instructorDocumentsAndAddressModel = $instructorDocumentsAndAddressModels[$id];
                $instructorIdentificationModel->hash = $instructorDocumentsAndAddressModel->hash;
    
                // Salvar os dados no banco de dados
                $instructorIdentificationModel->save();
                $instructorDocumentsAndAddressModel->save();
            }
        }
    }


    function saveTeachingMatrixes($teachingMatrixes)  
    {
        foreach ($teachingMatrixes as $teachingMatrixe) {
            $teachingMatrixeModel = new TeachingMatrixes();
            $teachingMatrixeModel->setDb2Connection(true);
            $teachingMatrixeModel->refreshMetaData();
            $teachingMatrixeModel->attributes = $teachingMatrixe;
            $teachingMatrixeModel->id = $teachingMatrixe['id'];
            $teachingMatrixeModel->save();
        }
    }
}
