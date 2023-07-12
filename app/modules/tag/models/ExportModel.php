<?php

/**
 * Summary of ExportModel
 */
class ExportModel
{
    /**
     * Summary of getInstructorsIdentification
     * @return array<array>
     */
    function getInstructorsIdentification()
    {
        $query = "SELECT * FROM instructor_identification";
        $instructors = Yii::app()->db->createCommand($query)->queryAll();

        $instructorModel = new InstructorIdentification();
        $instructorModel->setDb2Connection(false);
        $instructorModel->refreshMetaData();

        $instructorDocumentsModel = new InstructorDocumentsAndAddress();
        $instructorDocumentsModel->setDb2Connection(false);
        $instructorDocumentsModel->refreshMetaData();

        $instructorVariableDataModel = new InstructorVariableData();
        $instructorVariableDataModel->setDb2Connection(false);
        $instructorVariableDataModel->refreshMetaData();

        $arrayData = [];
        $instructorsData = [];
        $instructorDocumentsData = [];
        $instructorVarData = [];
        foreach ($instructors as $instructor) { 
            $instructorDocumentsData['instructor_documents_and_address'][] = $this->getInstructorDocumentsAndAddress($instructor['id']);  
            $instructorVarData['instructor_variable_data'][] = $this->getInstructorVariableData($instructor['id']);
            $instructorsData['instructor_identification'][] = $instructor;
        }

        $arrayData = array_merge($arrayData, $instructorsData);
        $arrayData = array_merge($arrayData, $instructorDocumentsData);
        $arrayData = array_merge($arrayData, $instructorVarData);

        return $arrayData;
    }

    /**
     * Summary of getInstructorDocumentsAndAddress
     * @return array<array>
     */
    function getInstructorDocumentsAndAddress($instructor)
    {
        $query = "SELECT * FROM instructor_documents_and_address WHERE id = " . $instructor;
        return Yii::app()->db->createCommand($query)->queryRow();
    }


    /**
     * Summary of getInstructorVariableData
     * @return array<array>
     */
    function getInstructorVariableData($id)
    {
        $query = "SELECT * FROM instructor_variable_data WHERE id = " . $id;
        return Yii::app()->db->createCommand($query)->queryRow();
    }

    /**
     * Summary of getInstructorsTeachingData
     * @return array<array>
     */
    function getInstructorsTeachingData()
    {
        $query = "SELECT * FROM instructor_teaching_data";
        $teachingData = Yii::app()->db->createCommand($query)->queryAll();
    
        $teachingDataModel = new InstructorTeachingData();
        $teachingDataModel->setDb2Connection(false);
        $teachingDataModel->refreshMetaData();
    
        $teachingDataData = [];
        foreach ($teachingData as $data) {
            $teachingDataData['instructor_teaching_data'][] = $data;
        }
    
        return $teachingDataData;
    }

    /**
     * Summary of getTeachingMatrixes
     * @return array<array>
     */
    function getTeachingMatrixes()
    {
        $query = "SELECT * FROM teaching_matrixes";
        $matrixes = Yii::app()->db->createCommand($query)->queryAll();
    
        $matrixModel = new TeachingMatrixes();
        $matrixModel->setDb2Connection(false);
        $matrixModel->refreshMetaData();
    
        $matrixesData = [];
        foreach ($matrixes as $matrix) {
            $matrixesData['teaching_matrixes'][] = $matrix;
        }
    
        return $matrixesData;
    }

    /**
     * Summary of getSchoolIdentification
     * @return array<array>
     */
    function getSchoolIdentification() 
    {
        $query = "SELECT * FROM school_identification";
        $schools = Yii::app()->db->createCommand($query)->queryAll();

        $schoolIdentificationModel = new SchoolIdentification();
        $schoolIdentificationModel->setDb2Connection(false);
        $schoolIdentificationModel->refreshMetaData();

        $schoolsData = [];
        foreach ($schools as $school) {
            $school['hash'] = hexdec(hash('crc32', $school['inep_id']));
            $schoolsData['school_identification'][] = $school;
        }

        return $schoolsData;
    }
    

    /**
     * Summary of getSchoolStructure
     * @return array<array>
     */
    function getSchoolStructure()
    {
        $query = "SELECT * FROM school_structure";
        $schoolStructures = Yii::app()->db->createCommand($query)->queryAll();

        $schoolStructureModel = new SchoolStructure();
        $schoolStructureModel->setDb2Connection(false);
        $schoolStructureModel->refreshMetaData();

        $schoolStructuresData = [];
        foreach ($schoolStructures as $schoolStructure) {
           $schoolStructure['hash'] = hexdec(hash('crc32', $schoolStructure['school_inep_id_fk']));
           $schoolStructuresData['schoolstructures'][] = $schoolStructure;
        }

        return $schoolStructuresData;
    }

    /**
     * Summary of getClassrooms
     * @return array<array>
     */
    function getClassrooms() 
    {
        $query = "SELECT * FROM classroom";
        $classrooms = Yii::app()->db->createCommand($query)->queryAll();

        $classroomsModel = new Classroom();
        $classroomsModel->setDb2Connection(false);
        $classroomsModel->refreshMetaData();

        $classroomsData = [];
        foreach ($classrooms as $classroom) {
            $classroom['hash'] = hexdec(hash('crc32', $classroom['name']));
            $classroomsData['classrooms'][] = $classroom;
        }

        return $classroomsData;
    }

    /**
     * Summary of getStudentIdentification
     * @return array<array>
     */
    function getStudentIdentification() 
    {
        $query = "SELECT * FROM student_identification";
        $students = Yii::app()->db->createCommand($query)->queryAll();

        $studentIdentificationModel = new StudentIdentification();
        $studentIdentificationModel->setDb2Connection(false);
        $studentIdentificationModel->refreshMetaData();

        $studentsData = [];
        foreach ($students as $student) {
            $student['hash'] = hexdec(hash('crc32', $student['name'].$student['birthday']));
            $studentsData['student_identification'][] = $student;
        }

        return $studentsData;
    }

    /**
     * Summary of getStudentDocumentsAndAddress
     * @return array<array>
     */
    function getStudentDocumentsAndAddress()
    {
        $query = "SELECT * FROM student_documents_and_address";
        $documentsAndAddress = Yii::app()->db->createCommand($query)->queryAll();
    
        $studentModel = new StudentDocumentsAndAddress();
        $studentModel->setDb2Connection(false);
        $studentModel->refreshMetaData();
    
        $studentDocumentsAndAddressData = [];
        foreach ($documentsAndAddress as $document) {
            $studentDocumentsAndAddressData['student_documents_and_address'][] = $document;
        }
    
        return $studentDocumentsAndAddressData;
    }

    /**
     * Summary of getStudentEnrollment
     * @return array
     */
    function getStudentEnrollment()  
    {
        $query = "SELECT * FROM student_enrollment";
        $studentEnrollments = Yii::app()->db->createCommand($query)->queryAll();

        $studentEnrollmentsData = [];
        foreach ($studentEnrollments as $studentEnrollment) {
            $studentEnrollmentsData['studentenrollments'] = $studentEnrollment;
        }

        return $studentEnrollmentsData;
    }
}
