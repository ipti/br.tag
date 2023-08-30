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
            $schoolInepFk = '35' . $inRelacaoClasses->getInCodEscola();

            //Aramazena as classes da escola de código $schoolInepFk
            $indexedByClasses = [];
            $allClasses = (object) Classroom::model()->findAll('school_inep_fk = :schoolInepFk', [':schoolInepFk' => $schoolInepFk]);
            foreach ($allClasses as $value) {
                $indexedByClasses[$value['gov_id']] = $value['gov_id'];
            }

            $classrooms = $mapper->Classrooms;  
            foreach($classrooms as $classroom) {     
                $classroomGovId = $classroom->gov_id;
                if($indexedByClasses[$classroomGovId] !== null){ //Verifica se a Classe já existe no TAG
                    $this->getStudentsFromClass($classroomGovId);
                } else {
                    $attributes = $classroom->getAttributes();
                    $createdClass = $this->createAndSaveNewClass($attributes, $classroom->gov_id);

                    if ($createdClass) {
                        $this->getStudentsFromClass($classroomGovId);      
                    } 
                } 
            }
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }       
    }

    function createAndSaveNewClass($attributes, $govId)
    {
        $class = new Classroom();
        $class->attributes = $attributes;
        $class->gov_id = $govId;

        if($class->validate() && $class->save()) 
            return true;
        else
            CVarDumper::dump($class->getErrors(), 10, true);  
    } 

    function getStudentsFromClass($classroomGovId)
    {
        $inNumClasse = new InFormacaoClasse($classroomGovId);
        $formacaoClasseSEDUseCase = new GetFormacaoClasseFromSEDUseCase();
        $formacaoClasseSEDUseCase->exec($inNumClasse); 
    }
}
