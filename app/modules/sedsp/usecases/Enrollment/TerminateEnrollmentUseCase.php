<?php

class TerminateEnrollmentUseCase 
{
    public function exec(InBaixarMatricula $inBaixarMatricula)
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addBaixarMatricula($inBaixarMatricula);
    }
}
