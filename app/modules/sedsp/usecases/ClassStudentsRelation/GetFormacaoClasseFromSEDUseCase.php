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
           
            foreach ($mapper->Students as $student) {
                $studentIdentification = new StudentIdentification();
                $studentIdentification->attributes = $student->getAttributes();

                if ($studentIdentification->validate() && $studentIdentification->save()) 
                    CVarDumper::dump('Aluno cadastrada com sucesso.', 10, true);
                else 
                    CVarDumper::dump('Não foi possível salvar os dados do aluno no banco de dados.', 10, true);
            }      
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }
    }
}