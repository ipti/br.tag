<?php

class ReallocateEnrollmentUseCase 
{
    public function exec(InRemanejarMatricula $inRemanejarMatricula)
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addRemanejarMatricula($inRemanejarMatricula);
    }
}
