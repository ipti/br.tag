<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class CreateStudent
{
    /**
     * Summary of exec
     * @param int $RA RA Number
     * @return StudentIdentification
     */
    public function exec($RA)
    {
        $ucstudent = new GetStudentFromSED();
        $student = $ucstudent->exec($RA);

        if(!isset($student->outErro)){
            echo 'SUCESSO<br>';
            echo '<pre>';
            var_dump($student);
            echo '</pre>';
        }else {
            echo 'ERRO<br>';
            echo '<pre>';
            var_dump($student);
            echo '</pre>';
        }
    }

}
