<?php

class MatrixRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Url da página de matriz curricular.
     */
    public function pageMatrix()
    {
        $this->tester->amOnPage('?r=curricularmatrix');
    }

    public function btnAdd()
    {
        $this->tester->click('#add-matrix');
    }

    public function stages($stage)
    {
        $this->tester->selectOption('#stages', $stage);
    }

    public function disciplines($disciplines)
    {
        $this->tester->selectOption('#disciplines', $disciplines);
    }

    public function workload($workload)
    {
        $this->tester->fillField('#workload', $workload);
    }

    public function credits($credits)
    {
        $this->tester->fillField('#credits', $credits);
    }
}
