<?php

class ExcluirMatriculaFromSEDUseCase
{
    /**
     * Summary of exec
     * @param InExcluirMatricula $inExcluirMatricula
     * @return OutErro|OutHandleApiResult
     */
    public function exec(InExcluirMatricula $inExcluirMatricula)
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addExcluirMatricula($inExcluirMatricula);
    }
}
