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
        $response = $enrollmentSEDDataSource->addExcluirMatricula($inExcluirMatricula);

        return $response;
    }
}
