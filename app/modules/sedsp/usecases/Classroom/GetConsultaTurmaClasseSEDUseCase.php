<?php

class GetConsultaTurmaClasseSEDUseCase
{
    /**
     * Summary of exec
     * @param InConsultaTurmaClasse $inConsultaTurmaClasse
     * @throws InvalidArgumentException
     */
    public function exec(InConsultaTurmaClasse $inConsultaTurmaClasse)
    {
        $inAnoLetivo = $inConsultaTurmaClasse->getInAnoLetivo();
        $inNumClasse = $inConsultaTurmaClasse->getInNumClasse();

        if (empty($inAnoLetivo) || strlen($inAnoLetivo) > 4)
            throw new InvalidArgumentException('O campo "inAnoLetivo" deve conter exatamente 4 caracteres.');

        if (empty($inNumClasse) || strlen($inNumClasse) > 9) 
            throw new InvalidArgumentException('O campo "inNumClasse" deve conter exatamente 9 caracteres.');
        

        $classroomSEDDataSource = new ClassroomSEDDataSource();
        $response = $classroomSEDDataSource->getConsultClass($inConsultaTurmaClasse);
        $mapper = (object) ClassroomMapper::parseToTAGConsultaTurmaClasse($response);


        $transaction = Yii::app()->db->beginTransaction();
        try {
            
            
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
            $transaction->rollback();
        }
    }
}
