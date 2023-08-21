<?php
class GetRelacaoClassesFromSEDUseCase 
{
    /**
     * Summary of exec
     * @param InRelacaoClasses $inRelacaoClasses
     */
    function exec(InRelacaoClasses $inRelacaoClasses)
    {

        try {
            $classes = new ClassStudentsRelationSEDDataSource();
            $response = $classes->getRelacaoClasses($inRelacaoClasses);
            $mapper = (object) ClassroomMapper::parseToTAGRelacaoClasses($response);

            $logSave = [];
            foreach($mapper->Classrooms as $classroom) {
                $class = new Classroom();
                $class->attributes = $classroom->getAttributes(); 

                if ($class->validate() && $class->save()) {
                    CVarDumper::dump($class->inep_id, 10, true);
                    $inNumClasse = new InFormacaoClasse($class->inep_id);
                    $formacaoClasseSEDUseCase = new GetFormacaoClasseFromSEDUseCase();
                    $formacaoClasseSEDUseCase->exec($inNumClasse);              
                    
                    $logSave[] = 'Classe '. $classroom->inep_id . ' - ' . $classroom->name . ' cadastrada com sucesso.';
                }else{
                    CVarDumper::dump($class->getErrors(), 10, true);  
                }   
            }
            CVarDumper::dump($logSave, 10, true);
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }       
    }
}
