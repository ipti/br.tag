<?php

Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');

class AddMatriculaToSEDUseCase
{
    /**
     * Summary of exec
     * @param mixed $inMatricularAluno
     * @return OutHandleApiResult
     */
    public function exec(InMatricularAluno $inMatricularAluno) 
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addMatricularAluno($inMatricularAluno);
    }
}
