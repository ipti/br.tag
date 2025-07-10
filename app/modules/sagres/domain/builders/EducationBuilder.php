<?php


use SagresEdu\EducacaoTType;


class EducationBuilder
{
    private EducacaoTType $educationType;
    private $referenceYear;
    private $referenceMonth;

    public function __construct($referenceYear, $referenceMonth)
    {
        $this->educationType = new EducacaoTType;
        $this->referenceYear = $referenceYear;
        $this->referenceMonth = $referenceMonth;
    }
    public function build(): EducacaoTType
    {

        $this->educationType->setPrestacaoContas($this->loadManagement());
        return $this->educationType;
    }

    public function loadManagement()
    {
        $management = new ManagementBuilder($this->referenceYear, $this->referenceMonth);
        return $management->build();
    }
}
?>