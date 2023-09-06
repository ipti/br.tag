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
     * @param int $ra RA Number
     * @return StudentIdentification
     */
    public function exec($ra)
    {
        $ucstudent = new GetStudentFromSED();
        return $ucstudent->exec($ra);
    }
}
