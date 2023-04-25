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
        return $student;
    }
}
