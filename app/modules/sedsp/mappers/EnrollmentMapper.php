<?php

class EnrollmentMapper 
{
    public function parseToSEDEnrollment($studentIdentificationTag, $studentEnrollmentTag)
    {
        $parseResult = [];
        
        $inAnoLetivo = Yii::app()->user->year;
        $inAluno = new InAluno($studentIdentificationTag->gov_id, null, 'SP');

        $numClass = Classroom::model()->findByPk($studentEnrollmentTag->classroom_fk);
        $inNumClass = $numClass->gov_id !== null ? $numClass->gov_id : $numClass->inep_id; 
        $inMatricula = new InMatricula(date('Y-m-d'), "00", $inNumClass); // $inNumAluno -> Esse campo é gerado automaticamente se preenchido com “00”

        $classroomMapper = new ClassroomMapper;
        $edcensoStage = $numClass->edcenso_stage_vs_modality_fk;
        $stage = (object) $classroomMapper->convertStageToTipoEnsino($edcensoStage);
        $inNivelEnsino = new InNivelEnsino($stage->tipoEnsino, $stage->serieAno);

        //Matrículas
        $inEnrollment = new InMatricularAluno;
        $inEnrollment->setInAnoLetivo($inAnoLetivo);
        $inEnrollment->setInAluno($inAluno);
        $inEnrollment->setInMatricula($inMatricula);
        $inEnrollment->setInNivelEnsino($inNivelEnsino);

        $parseResult["Enrollment"] = $inEnrollment;

        return $parseResult;
    }
}
