<?php

Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');

class AddMatriculaToSEDUseCase
{
    /**
     * Summary of exec.
     * @return OutErro|OutHandleApiResult
     */
    public function exec(InMatricularAluno $inMatricularAluno)
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource();

        return $enrollmentSEDDataSource->addMatricularAluno($inMatricularAluno);
    }
}
