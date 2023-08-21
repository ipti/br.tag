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
                    $logSave[] = 'Classe '. $classroom->inep_id . ' - ' . $classroom->name . ' cadastrada com sucesso.';
                }else{
                    CVarDumper::dump($class->getErrors(), 10, true);  
                }   
            }
            CVarDumper::dump($logSave, 10, true);
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }

       /*  //Realiza o cadastro da turma juntamente com seus alunos da sedsp no TAG.
		$inNumClasse = new InFormacaoClasse("262429087");
		$formacaoClasseSEDUseCase = new GetFormacaoClasseFromSEDUseCase();
		$formacaoClasseSEDUseCase->exec($inNumClasse); */
    }
}
