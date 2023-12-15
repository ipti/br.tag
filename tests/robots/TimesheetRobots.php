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
        $this->tester->wait(2);
    }

    /**
     * Botão de gerar quadro automático.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnGenerate()
    {
        $this->tester->waitForElementClickable('.wide-button', 10);
        $this->tester->executeJS("document.querySelector('.wide-button').click();");
    }

    /**
     * Botão para confirmar: Gerar outro Quadro de Horário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnConfirm()
    {
        $this->tester->waitForElementClickable('.confirm-timesheet-generation', 10);
        $this->tester->executeJS("document.querySelector('.confirm-timesheet-generation').click();");
    }

    /**
     * Seleciona a turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classroom($classroom)
    {
        $this->tester->selectOption('#classroom_fk', $classroom);
        $this->tester->wait(2);
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
