<?php

class EnrollmentMapper 
{
    public function parseToSEDEnrollment($studentIdentificationTag, $studentEnrollmentTag)
    {

        $parseResult = [];

        //Matrículas
        $inEnrollment = new InMatricularAluno;
        $inEnrollment->setInAnoLetivo(Yii::app()->user->year);
        $inEnrollment->setInAluno(
            new InAluno(
                $studentIdentificationTag->gov_id, 
                null, 'SP'
            )
        );

        $numClass = Classroom::model()->findByPk($studentEnrollmentTag->classroom_fk);
        $inNumClass = $numClass->gov_id !== null ? $numClass->gov_id : $numClass->inep_id;

        $inEnrollment->setInMatricula(
            new InMatricula(
                $studentEnrollmentTag->create_date, 
                "00", 
                $inNumClass
            )
        );

        $modality = EdcensoStageVsModality::model()->findByPk($studentEnrollmentTag->edcenso_stage_vs_modality_fk);
        $inEnrollment->setInNivelEnsino(new InNivelEnsino($modality, '4'));


        $parseResult["Enrollment"] = $inEnrollment;

        return $parseResult;
    }
}
