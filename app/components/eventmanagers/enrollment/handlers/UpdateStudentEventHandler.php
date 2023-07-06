<?php

Yii::import('application.modules.sedsp.usecases.UpdateStudentOnSED');
Yii::import('application.modules.sedsp.usecases.AddStudentToSED');
class UpdateStudentEventHandler
{
    public static function getSubscribers(){
        return [
            UpdateStudentEvent::NAME => function($event){
                UpdateStudentEventHandler::handleUpdate($event);
            },
            CreateStudentEvent::NAME => function($event)  {
                UpdateStudentEventHandler::handleCreate($event);
            }
        ];
    }
    /**
     * @param UpdateStudentEvent $event
     */     
    private static function handleUpdate($event)
    
    {        
        try {
            $add_usecase = new UpdateStudentOnSED();
            $result = $add_usecase->exec($event->student->id);            
            Yii::log($result, CLogger::LEVEL_ERROR);
        } catch (\Throwable $th) {            
           Yii::log($th, CLogger::LEVEL_ERROR);
        }        
    }
    private static function handleCreate ($event){
        try {
            $add_usecase = new AddStudentToSED();
            $result = $add_usecase->exec($event->student->id);
            Yii::log(var_dump($result), CLogger::LEVEL_ERROR);
        } catch (\Throwable $th) {            
           Yii::log($th, CLogger::LEVEL_ERROR);
        }        
    }
}

?>