<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property ClassroomSEDDataSource $studentSEDDataSource
 */
class CreateClassroom
{
    /**
     * Summary of exec
     * @param int $RA RA Number
     * @return Classroom
     */
    public function exec($num_classe)
    {
        $ucclassroom = new GetClassroomFromSED();
        $classroom = $ucclassroom->exec($num_classe);
        return $classroom;
    }
}
