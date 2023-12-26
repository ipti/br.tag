<?php

Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');

class IsEnrolledUseCase 
{
    public function exec(InExibirMatriculaClasseRA $inExibirMatriculaClasseRA)  
    {
        $enrollmentSEDDataSource = new EnrollmentSEDDataSource;
        return $enrollmentSEDDataSource->getExibirMatriculaClasseRA($inExibirMatriculaClasseRA);
    }
}
