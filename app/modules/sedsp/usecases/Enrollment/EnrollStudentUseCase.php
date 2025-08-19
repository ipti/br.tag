<?php

class EnrollStudentUseCase 
{
    public function exec(InscreverAluno $inscreverAluno)
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addInscreverAluno($inscreverAluno);
    }
}
