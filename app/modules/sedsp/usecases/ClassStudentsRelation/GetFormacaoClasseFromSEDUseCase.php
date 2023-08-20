<?php


/**
 * Summary of GetFormacaoClasseSEDUseCase
 */
class GetFormacaoClasseFromSEDUseCase
{

    /**
     * Summary of exec
     * @param InFormacaoClasse $inNumClasse
     * @throws InvalidArgumentException 
     */
    function exec(InFormacaoClasse $inNumClasse)
    {
        if (empty($inNumClasse->getInNumClasse()))
            throw new InvalidArgumentException("Número da classe (turma) é obrigatório.");

        if (strlen($inNumClasse->getInNumClasse()) > 9)
            throw new InvalidArgumentException("Número da classe (turma) deve ter no máximo 9 caracteres.");

        $formacaoClasseDataSource = new ClassStudentsRelationSEDDataSource();
        $response = $formacaoClasseDataSource->getClassroom($inNumClasse);
        $classroom = Classroom::model()->find('inep_id = :inep_id', [':inep_id' => $response->getOutNumClasse()]);
        
        if ($classroom !== null) {
            CVarDumper::dump('A Classe {' . $classroom->school_inep_fk . '-' . $classroom->name . '} já está cadastrada no TAG!', 10, true);
            return;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $mapper = (object) ClassroomMapper::parseToTAGFormacaoClasse($response);
            $classroom = new Classroom();
            $classroom->attributes = $mapper->Classroom->getAttributes();
            
            if ($classroom->validate() && $classroom->save()) {
                foreach ($mapper->Students as $student) {
                    $studentIdentification = new StudentIdentification();
                    $studentIdentification->attributes = $student->getAttributes();

                    if ($studentIdentification->validate() && $studentIdentification->save()) 
                        CVarDumper::dump('Aluno ' . $student->getOutNumRa . ' cadastrada com sucesso.', 10, true);
                    else 
                        throw new SedspException('Não foi possível salvar os dados do aluno no banco de dados.');
                }     
            } else {
                throw new SedspException('Os dados da classe não são válidos.');
            }
            $transaction->commit();
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
            $transaction->rollback();
        }
    }
}