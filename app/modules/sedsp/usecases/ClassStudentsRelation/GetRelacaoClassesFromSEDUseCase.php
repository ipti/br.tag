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

            $schoolInepFk = '35'.$inRelacaoClasses->getInCodEscola();

            //Aramazena as classes da escola de código $schoolInepFk
            $indexedByClasses = [];
            $allClasses = (object) Classroom::model()->findAll('school_inep_fk = :schoolInepFk', [':schoolInepFk' => $schoolInepFk]);
            foreach ($allClasses as $value) {
                $indexedByClasses[$value['inep_id']] = $value['inep_id'];
            }

            $logSave = [];
            foreach($mapper->Classrooms as $classroom) {   
                if($indexedByClasses[$classroom->inep_id] !== null){
                    //Classe já existe no TAG
                    $inNumClasse = new InFormacaoClasse($classroom->inep_id);
                    $formacaoClasseSEDUseCase = new GetFormacaoClasseFromSEDUseCase();
                    $formacaoClasseSEDUseCase->exec($inNumClasse); 
                }else{
                    //Cria uma nova classe
                    $class = new Classroom();
                    $class->attributes = $classroom->getAttributes(); 

                    if ($class->validate() && $class->save()) {
                        $inNumClasse = new InFormacaoClasse($classroom->inep_id);
                        $formacaoClasseSEDUseCase = new GetFormacaoClasseFromSEDUseCase();
                        $formacaoClasseSEDUseCase->exec($inNumClasse);  
                        
                        $logSave[] = 'Classe '. $classroom->inep_id . ' - ' . $classroom->name . ' cadastrada com sucesso.';
                    }else{
                        CVarDumper::dump($class->getErrors(), 10, true);  
                    } 
                }  
            }
            CVarDumper::dump($logSave, 10, true);
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }       
    }
}
