<?php

Yii::import('system.base.CApplicationComponent');
Yii::import('application.components.eventmanagers.logger.events.LogEvent');
Yii::import('application.components.eventmanagers.logger.handlers.LogEventHandler');
class LoggerManager extends CApplicationComponent
{
    public function __construct()
    {
        $this->attachEventHandlers();
    }

    private function attachEventHandlers()
    {        
        $logEventHandler = new LogEventHandler();        
        $this->onRegisterLog = array($logEventHandler, 'handleLog');
    }
    public function logCreate($reference, $referenceIds, $additionalInfo)
    {
        $event = new LogEvent($this, $reference, $referenceIds, LogEvent::CREATE, $additionalInfo);        
        $this->onRegisterLog($event);
    }

    public function logUpdate($reference, $referenceIds, $additionalInfo)
    {
        $event = new LogEvent($this, $reference, $referenceIds, LogEvent::UPDATE, $additionalInfo);        
        $this->onRegisterLog($event);
    }

    public function logDelete($reference, $referenceIds, $additionalInfo)
    {
        $event = new LogEvent($this, $reference, $referenceIds, LogEvent::DELETE, $additionalInfo);        
        $this->onRegisterLog($event);
    }

    private function onRegisterLog($event){
        $this->raiseEvent('onRegisterLog', $event);
    }

}

?>