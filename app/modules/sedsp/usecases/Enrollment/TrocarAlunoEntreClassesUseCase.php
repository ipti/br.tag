<?php

class TrocarAlunoEntreClassesUseCase 
{
    public function exec(InTrocarAlunoEntreClasses $inTrocarAlunoEntreClasses) 
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->addTrocarAlunoEntreClasses($inTrocarAlunoEntreClasses);
    }
}
