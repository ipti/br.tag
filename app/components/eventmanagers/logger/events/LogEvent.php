<?php
Yii::import('system.base.CEvent');
class LogEvent extends CEvent
{

    const NAME = "onRegisterLog";
    const CREATE = "C";
    const UPDATE = "U";
    const DELETE = "U";

    public $reference;
    public $referenceIds;
    public $crud;
    public $additionalInfo;


    /**
     * Summary of __construct
     * @param mixed $sender
     * @param string $reference
     * @param string $referenceIds
     * @param string $crud
     * @param string $additionalInfo
     */
    public function __construct($sender, $reference, $referenceIds, $crud, $additionalInfo = null)
    {
        $this->reference = $reference;
        $this->referenceIds = $referenceIds;
        $this->crud = $crud;
        $this->additionalInfo = $additionalInfo;

        parent::__construct($sender);
    }

    public function __toString()
    {
        return $this->mapActions($this->crud) ." : ". $this->reference ." | ". $this->referenceIds ." | ". $this->additionalInfo; 
    }


    private function mapActions($action){
        $actionsMap = [
            "C" => "CREATE",
            "U" => "UPDATE",
            "D" => "DELETE",
        ];

        return  isset($actionsMap[$action]) ? $actionsMap[$action] : null;
    }
}

?>