<?php

Yii::import('application.components.eventmanagers.logger.events.*');
class LogEventHandler
{

    public static function getSubscribers(){
        return [
            LogEvent::NAME => 'handleLog',            
        ];
    }
    /**
     * @param LogEvent $event
     */
    public static function handleLog($event)
    {        
        $clientIP = Yii::app()->request->getUserHostAddress();
        $user_id = Yii::app()->user->loginInfos->username;

        Yii::log($event ." - ".  $user_id ."(". $clientIP .")");        
        Log::model()->saveAction($event->reference, $event->referenceIds, $event->crud, $event->additionalInfo);
    }    
}

?>