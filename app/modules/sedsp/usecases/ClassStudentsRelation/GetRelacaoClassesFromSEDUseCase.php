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

            $mapper = (object) ClassroomMapper::parseToTAGRelacaoClasses($response);
            $schoolInepFk = SchoolMapper::mapToTAGInepId($inRelacaoClasses->getInCodEscola());

            //Aramazena as classes da escola de código $schoolInepFk
            $indexedByClasses = [];
            $allClasses = (object) Classroom::model()->findAll(
                'school_inep_fk = :schoolInepFk', [':schoolInepFk' => $schoolInepFk]
            );
            foreach ($allClasses as $value) {
                $indexedByClasses[$value['gov_id']] = $value['gov_id'];
            }

            $status = false;
            $classrooms = $mapper->Classrooms;

            if(empty($classrooms)){
                $log = new LogError();
                $log->salvarDadosEmArquivo($response->getOutErro());
                return 2;
            }

            foreach($classrooms as $classroom) {
                $classroomGovId = $classroom->gov_id;

                if($indexedByClasses[$classroomGovId] !== null){ //Verifica se a Classe já existe no TAG
                    $status = true;
                } else {
                    $attributes = $classroom->getAttributes();
                    $createdClass = $this->createAndSaveNewClass($attributes, $classroom->gov_id);
                 
                    if ($createdClass) {
                        $status = true;
                    }
                }
            }

            return $status;
        } catch (Exception $e) {
            $log = new LogError();
            $log->salvarDadosEmArquivo($e->getMessage());
        }
    }


    public function createAndSaveNewClass($attributes, $govId)
    {
        $class = new Classroom();
        $class->attributes = $attributes;
        $class->gov_id = $govId;

        if($class->validate() && $class->save()) {
            return true;
        } else {
            $log = new LogError();
            $log->salvarDadosEmArquivo($class->getErrors());
        }
    }

    public function getStudentsFromClass($numClasse)
    {
        $inNumClasse = new InFormacaoClasse($numClasse);
        $formacaoClasseSEDUseCase = new GetFormacaoClasseFromSEDUseCase();
        return $formacaoClasseSEDUseCase->exec($inNumClasse);
    }
}
