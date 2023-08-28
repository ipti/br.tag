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

            $logSave = [];
            $classrooms = $mapper->Classrooms;

            $transaction = Yii::app()->db->beginTransaction();
            $allClassesSaved = true;

            foreach($classrooms as $classroom) {
                $classroomGovId = $classroom->gov_id;

                //Classe já existe no TAG
                if($indexedByClasses[$classroomGovId] !== null){
                    $this->getStudentsFromClass($classroomGovId);
                } else {
                    $attributes = $classroom->getAttributes();
                    $createdClass = $this->createAndSaveNewClass($attributes, $classroom->gov_id);

                    if ($createdClass) {
                        $this->getStudentsFromClass($classroomGovId);      
                    } else {
                        $allClassesSaved = false;
                    }
                }  
            }

            if ($allClassesSaved) {
                $transaction->commit();
            } else {
                $transaction->rollback();
            }

            CVarDumper::dump($logSave, 10, true);
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
