<?php

Yii::import('system.base.CApplicationComponent');
Yii::import('application.components.eventmanagers.enrollment.events.*');
Yii::import('application.components.eventmanagers.enrollment.handlers.*');
Yii::import('application.components.eventmanagers.logger.handlers.*');
class StudentManager extends CApplicationComponent
{
    public function __construct()
    {
        // Attach event handlers
        $this->attachHandler();
    }

    private function attachHandler()
    {        
        $event_handler = new UpdateStudentEventHandler();
        $subscribers = $event_handler->getSubscribers();
        $this->attachSubscribers($subscribers);
        
   }

    public function attachSubscribers($subscribers)
    {        
        foreach ($subscribers as $event_name => $action) {
            $this->attachEventHandler($event_name, $action);
        }
    }
    public function update($student)
    {        
        $event = new UpdateStudentEvent($this, $student);
        $this->raiseEvent(UpdateStudentEvent::NAME, $event);   
    }
    public function create($student)
    {        
        $event = new CreateStudentEvent($this, $student);
        $this->raiseEvent(CreateStudentEvent::NAME, $event);               
    }
    
    private function onStudentUpdated($event){       
    }
    private function onStudentCreated($event){}
    private function onRegisterLog($event){}
}

?>