<?php

class Adapter implements ExportableInterface, ImportableInterface
{
    /**
     * Summary of export
     * @return void
     */
    function export($data){
        $databaseName = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();
        $pathFileJson = "./app/export/InfoTagJSON/$databaseName.json";

        try {                    
            $host = getenv("HOST_DB_TAG");
            Yii::app()->db->setActive(false);
            Yii::app()->db->connectionString = "mysql:host=$host;dbname=$databaseName";
            Yii::app()->db->setActive(true);
            
            file_put_contents($pathFileJson, json_encode($data));

            // Envia o arquivo JSON como download
            header("Content-Disposition: attachment; filename=\"" . basename($pathFileJson) . "\"");
            header("Content-Type: application/force-download");
            header("Content-Length: " . filesize($pathFileJson));
            header("Connection: close"); 
            readfile($pathFileJson);
            
        } catch (Exception $e) {
            echo "Ocorreu um erro durante o processamento: " . $e->getMessage();
            if (file_exists($pathFileJson)) {
                unlink($pathFileJson);
            }
        } finally {
            ini_set('memory_limit', ini_get('memory_limit'));
        }
    }


    function import($pathFileJson) {
        try { 
            $data = json_decode(file_get_contents($pathFileJson), true);  
            $importModel = new ImportModel();  
            
            $transaction = Yii::app()->db->beginTransaction();
            Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

            $importModel->saveSchoolIdentificationsDB($data['school_identification']);
            $importModel->saveSchoolStructureDB($data['schoolstructures']);
            $importModel->saveClassroomsDB($data['classrooms']);
            
            $importModel->saveInstructorIdentificationDB($data['instructor_identification']);  
            $importModel->saveInstructorsTeachingDataDB($data['instructor_teaching_data']);
            $importModel->saveInstructorVariableDataDB($data['instructorvariabledata']);
            $importModel->saveInstructorDocumentsAndAddressDB($data['instructor_documents_and_address']); 
            $importModel->saveTeachingMatrixes($data['teaching_matrixes']);

            $importModel->saveStudentIdentificationDB($data['student_identification']);
            $importModel->saveStudentDocumentsAndAddressDB($data['student_documents_and_address']);
            $importModel->saveStudentEnrollmentDB($data['studentenrollments']);
            
            Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute(); 
            $transaction->commit(); 
        } catch (PDOException $e) {
            $transaction->rollback();
            throw new Exception('Erro na importação: ' . $e->getMessage());
        }
    }
}
