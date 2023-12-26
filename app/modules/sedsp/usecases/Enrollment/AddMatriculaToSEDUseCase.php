<?php

Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');

class AddMatriculaToSEDUseCase
{
    /**
     * Summary of exec
     * @param InMatricularAluno $inMatricularAluno
     * @return OutErro|OutHandleApiResult
     */
    public function exec(InMatricularAluno $inMatricularAluno) 
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addMatricularAluno($inMatricularAluno);
    }
}
