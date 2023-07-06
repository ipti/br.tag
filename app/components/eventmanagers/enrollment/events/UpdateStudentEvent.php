<?php
Yii::import('system.base.CEvent');
class UpdateStudentEvent extends CEvent
{
    const NAME = "onStudentUpdated";
    public $student;

    public function __construct($sender, $student)
    {
        $this->student = $student;
        parent::__construct($sender);
    }

    
}

?>