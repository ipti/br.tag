<?php

/**
 * UseCase class GetFormacaoClasseSED
 */
class GetFormacaoClasseSEDUseCase 
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
        return ClassroomMapper::parseToTAGFormacaoClasse($response);
    }
}
