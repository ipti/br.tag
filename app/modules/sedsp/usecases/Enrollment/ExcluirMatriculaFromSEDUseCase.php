<?php

Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');

class ExcluirMatriculaFromSEDUseCase
{
    /**
     * Summary of exec.
     * @return OutErro|OutHandleApiResult
     */
    public function exec(InExcluirMatricula $inExcluirMatricula)
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource();

        return $enrollmentSEDDataSource->addExcluirMatricula($inExcluirMatricula);
    }
}
