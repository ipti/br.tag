<?php
Yii::import('system.base.CEvent');
class CreateStudentEvent extends CEvent
{
    const NAME = "onStudentCreated";
    public $student;

    public function __construct($sender, $student)
    {
        $this->student = $student;
        parent::__construct($sender);
    }
}

?>