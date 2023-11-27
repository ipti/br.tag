<?php

class TimesheetRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Url da página de quadro de horário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageTimesheet()
    {
        $this->tester->amOnPage('?r=timesheet');
    }

    /**
     * Botão de gerar quadro automático.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnGenerate()
    {
        $this->tester->click('.btn-generate-timesheet');
    }

    /**
     * Seleciona a turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classroom($classroom)
    {
        $this->tester->selectOption('#classroom_fk', $classroom);
    }

    /**
     *  Replicar alterações para todas as semanas subsequentes.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function replicate()
    {
        $this->tester->click('.replicate-actions-checkbox replicate-actions');
    }
}
