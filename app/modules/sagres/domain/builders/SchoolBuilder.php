<?php

use SagresEdu\EscolaTType;
class SchoolBuilder
{
    private $referenceYear;
    private $referenceMonth;
    private $withoutCpf;
    private $finalClass;
    private EscolaTType $escola;
    public function __construct($finalClass, $referenceYear, $referenceMonth, $withoutCpf)
    {
        $this->escola = new EscolaTType;
        $this->referenceYear = $referenceYear;
        $this->referenceMonth = $referenceMonth;
        $this->withoutCpf = $withoutCpf;
        $this->finalClass = $finalClass;

    }

    private function loadClassrooms($inepId): self
    {
        $classromExtractor = new ClassroomExtractor($inepId, $this->referenceYear, $this->finalClass, $this->withoutCpf);
        $classrooms = (new ClassroomAdapter($classromExtractor))->parse();

        $this->escola->setTurma($classrooms);

        return $this;
    }
    private function loadMenus(): self
    {
        return $this;
    }

    /**
     * @return EscolaTType[]
     */
    public function build(): array
    {
        $schoolObj = new SchoolExtractor();
        $schoolData = $schoolObj->execute();
        $schoolList = [];

        foreach ($schoolData as $school) {
            $inepId = $school->inep_id;
            $this->loadClassrooms($inepId);

        }
        $schoolList[] = $this->escola;
        return $schoolList;


    }


}
?>