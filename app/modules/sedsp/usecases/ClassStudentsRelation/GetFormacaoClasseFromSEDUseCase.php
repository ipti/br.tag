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
        if (empty($inNumClasse->getInNumClasse())) {
            throw new InvalidArgumentException("Número da classe (turma) é obrigatório.");
        }
        
        if (strlen($inNumClasse->getInNumClasse()) > 9) {
            throw new InvalidArgumentException("Número da classe (turma) deve ter no máximo 9 caracteres.");
        }

        $formacaoClasseDataSource = new ClassStudentsRelationSEDDataSource(); 
        $response = $formacaoClasseDataSource->getClassroom($inNumClasse);
        $mapper = (object) ClassroomMapper::parseToTAGFormacaoClasse($response);
        
        $classroom = Classroom::model()->find('inep_id = :inep_id', [':inep_id' => $mapper->Classroom->inep_id]);

        if ($classroom !== null) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Unidade gestora solicitada não existe!'));
        } else {
            $classroom = new Classroom();
            $classroom->attributes = $mapper->Classroom->getAttributes();

            $transaction = Yii::app()->db->beginTransaction();
            try {
                if ($classroom->validate() && $classroom->save()) {
                    foreach ($mapper->Students as $student) {
                        $studentIdentification = new StudentIdentification();
                        $studentIdentification->attributes = $student->getAttributes();

                        if ($studentIdentification->validate() && $studentIdentification->save()) {
                            CVarDumper::dump('Classroom cadastrada com sucesso.', 10, true);
                        } else {
                            throw new SedspException('Não foi possível salvar os dados do aluno no banco de dados.');
                        }
                    }
                    $transaction->commit();
                } else {
                    throw new SedspException('Os dados da classe não são válidos.');
                }
            } catch (Exception $e) {
                CVarDumper::dump($e->getMessage(), 10, true);
                $transaction->rollback();
            }
        }
    }
}
