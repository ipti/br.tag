<?php

class CoursePlanRobots
{

    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Url da página de criar plano de aula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageCourse()
    {
        $this->tester->amOnPage('/?r=courseplan/create');
    }

    public function btnPrevious()
    {
        $this->tester->click('.prev');
    }

    public function btnNext()
    {
        $this->tester->click('.next');
    }

    public function btnSave()
    {
        $this->tester->click('.last');
    }

    

}
