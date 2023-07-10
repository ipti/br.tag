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
            $classroomModel->setScenario('search');
            $classroomModel->setDb2Connection(true);
            $classroomModel->refreshMetaData();
            $classroomModel->attributes = $classroom;
            $classroomModel->save();
        }
    }

    function saveStudentDocumentsAndAddressDB($jsonDocumentsAddress) {
        foreach ($jsonDocumentsAddress as $documentsaddress) {
            $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
            $studentDocumentsAndAddress->setDb2Connection(true);
            $studentDocumentsAndAddress->refreshMetaData();
            $studentDocumentsAndAddress->attributes = $documentsaddress;
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
            $studentEnrollment->save();
        }
    }

    function saveInstructorsTeachingDataDB($jsonInstructorsTeachingDatas)
    {
        foreach ($jsonInstructorsTeachingDatas as $instructorsTeachingData) {
            $instructorTeachingData = new InstructorTeachingData();
            $instructorTeachingData->setDb2Connection(true);
            $instructorTeachingData->refreshMetaData();
            $instructorTeachingData->attributes = $instructorsTeachingData;
            $instructorTeachingData->save();
         }
    }

    function saveInstructorVariableDataDB($instructorVariableDatas)
    {
        foreach ($instructorVariableDatas as $instructorVariableData) {
            $instructorVariableDataModel = new InstructorVariableData();
            $instructorVariableDataModel->setDb2Connection(true);
            $instructorVariableDataModel->refreshMetaData();
            $instructorVariableDataModel->attributes = $instructorVariableData;
            $instructorVariableDataModel->save();
        }
    }

    function saveInstructorIdentificationDB($instructorIdentifications) 
    {
        foreach ($instructorIdentifications as $instructorIdentification) {
            $instructorIdentificationModel = new InstructorIdentification();
            $instructorIdentificationModel->setDb2Connection(true);
            $instructorIdentificationModel->refreshMetaData();
            $instructorIdentificationModel->attributes = $instructorIdentification;
            $instructorIdentificationModel->id = $instructorIdentification['id'];
            $instructorIdentificationModel->save();
        }
    }
    
    function saveInstructorDocumentsAndAddressDB($instructorDocumentsAndAddresses)
    {
        foreach ($instructorDocumentsAndAddresses as $instructorDocumentsAndAddress) {
            $instructorDocumentsAndAddressModel = new InstructorDocumentsAndAddress();
            $instructorDocumentsAndAddressModel->setDb2Connection(true);
            $instructorDocumentsAndAddressModel->refreshMetaData();
            $instructorDocumentsAndAddressModel->attributes = $instructorDocumentsAndAddress;
            $instructorDocumentsAndAddressModel->id = $instructorDocumentsAndAddress['id'];
            $instructorDocumentsAndAddressModel->save();
        }
    }

    function saveTeachingMatrixes($teachingMatrixes)  
    {
        foreach ($teachingMatrixes as $teachingMatrixe) {
            $teachingMatrixeModel = new TeachingMatrixes();
            $teachingMatrixeModel->setDb2Connection(true);
            $teachingMatrixeModel->refreshMetaData();
            $teachingMatrixeModel->attributes = $teachingMatrixe;
            $teachingMatrixeModel->save();
        }
    }

    function saveStudentIdentificationDB($studentIdentifications)
    {
        foreach ($studentIdentifications as $studentIdentification) {
            $studentIdentificationMode = new StudentIdentification();
            $studentIdentificationMode->setDb2Connection(true);
            $studentIdentificationMode->refreshMetaData();
            $studentIdentificationMode->attributes = $studentIdentification;
            $studentIdentificationMode->save();
        }
    }
}
