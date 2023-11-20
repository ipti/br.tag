<?php 

class DeleteEnrollmentUseCase 
{
    public function exec(InExcluirMatricula $inExcluirMatricula)
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addExcluirMatricula($inExcluirMatricula);
    }
}
