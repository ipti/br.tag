<?php

class GetRelacaoClassesFromSEDUseCase
{
    /**
     * Summary of exec
     * @param InRelacaoClasses $inRelacaoClasses
     */
    public function exec(InRelacaoClasses $inRelacaoClasses)
    {
        try {
            $classes = new ClassStudentsRelationSEDDataSource();
            $response = $classes->getRelacaoClasses($inRelacaoClasses);

            $mapper = (object)ClassroomMapper::parseToTAGRelacaoClasses($response);
            $classrooms = $mapper->Classrooms;

            if (empty($classrooms)) {
                $log = new LogError();
                $log->salvarDadosEmArquivo($response->getOutErro());
                return 2;
            }

            $status = false;
            foreach ($classrooms as $classroom) {
                $createdClass = $this->createAndSaveNewClass($classroom);

                if ($createdClass) {
                    $status = true;
                }
            }

            return $status;
        } catch (Exception $e) {
            $log = new LogError();
            $log->salvarDadosEmArquivo($e->getMessage());
        }
    }


    public function createAndSaveNewClass($classroom)
    {
        if ($classroom->validate() && $classroom->save()) {
            return true;
        } else {
            $log = new LogError();
            $log->salvarDadosEmArquivo($classroom->getErrors());
        }
    }

    public function getStudentsFromClass($numClasse)
    {
        $inNumClasse = new InFormacaoClasse($numClasse);
        $formacaoClasseSEDUseCase = new GetFormacaoClasseFromSEDUseCase();
        return $formacaoClasseSEDUseCase->exec($inNumClasse);
    }
}
