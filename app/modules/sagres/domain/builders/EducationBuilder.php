<?php


use SagresEdu\EducacaoTType;


class EducationBuilder
{
    private EducacaoTType $educationType;
    private $finalClass;
    private $referenceYear;
    private $referenceMonth;
    private $withoutCpf;
    private $noMovement;

    public function __construct($referenceYear, $referenceMonth, $finalClass, $withoutCpf, $noMovement)
    {
        $this->educationType = new EducacaoTType;
        $this->referenceYear = $referenceYear;
        $this->referenceMonth = $referenceMonth;
        $this->finalClass = $finalClass;
        $this->withoutCpf = $withoutCpf;
        $this->noMovement = $noMovement;
    }
    public function build(): EducacaoTType
    {
        if ($this->noMovement) {
            $this->educationType
                ->setPrestacaoContas($this->loadManagement());
            return $this->educationType;
        }
        $this->educationType
            ->setPrestacaoContas($this->loadManagement())
            ->setEscola($this->loadSchool());
        return $this->educationType;
    }

    public function loadManagement()
    {
        $management = new ManagementBuilder($this->referenceYear, $this->referenceMonth);
        return $management->build();
    }
    private function loadSchool()
    {
        $school = new SchoolBuilder($this->finalClass, $this->referenceYear, $this->referenceMonth, $this->withoutCpf);
        return $school->build();
    }
}
?>