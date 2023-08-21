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

        $formacaoClasseDataSource = new ClassStudentsRelationSEDDataSource();
        $response = $formacaoClasseDataSource->getClassroom($inNumClasse);

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
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }
    }
}