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
        try {
            $ucstudent = new GetStudentFromSED();
            $student = $ucstudent->exec($RA);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $student;
    }
}
