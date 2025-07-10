<?php
class SchoolBuilder
{
    private $inepId;
    private $referenceYear;
    private $referenceMonth;
    private EscolaTType $escola;
    public function __construct($inepId, $referenceYear, $referenceMonth)
    {
        $this->escola = new EscolaTType;
        $this->inepId = $inepId;
        $this->referenceYear = $referenceYear;
        $this->referenceMonth = $referenceMonth;

    }

    private function loadClassrooms(): self
    {
        $classromExtractor = new ClassroomExtractor($this->inepId, $this->referenceYear);
        $classrooms = (new ClassroomAdapter($classromExtractor))->parse();

        $this->escola->setTurma($classrooms);

        return $this;
    }
    private function loadMenus(): self
    {
        return $this;
    }

    public function build(): EscolaTType
    {
        $this->loadClassrooms();
        $this->loadMenus();


        return $this->escola;

    }

}
?>