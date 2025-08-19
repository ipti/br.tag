<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property SchoolSEDDataSource $schoolSEDDataSource
 */
class CreateSchool
{
    /**
     * Summary of exec
     * @param int $RA RA Number
     * @return SchoolIdentification
     */
    public function exec($school_name, $school_mun)
    {
        $ucschool = new GetSchoolFromSED();
        $school = $ucschool->exec($school_name, $school_mun);
        if($school["SchoolIdentification"]->inep_id != null) {
            return $school;
        }else {
            throw new Exception("Ocorreu um erro ao cadastrar a escola. Certifique-se de inserir dados válidos.", 500);
        }
    }
}
