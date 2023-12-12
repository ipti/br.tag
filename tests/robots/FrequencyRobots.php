<?php

class FrequencyRobots
{
    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * URL da página de frequência.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageFrequency()
    {
        $this->tester->amOnpage('?r=classes/frequency');
        $this->tester->wait(2);
    }

    /**
     * Seleciona o mês.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function month($month)
    {
        $this->tester->selectOption('#month', $month);
    }

    /**
     * Seleciona a turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classroom($classroom)
    {
        $this->tester->selectOption('#classroom', $classroom);
    }

    /**
     * Seleciona o componente curricular/eixo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function component($component)
    {
        $this->tester->selectOption('#disciplines', $component);
    }

    /**
     * Selecionar o ano.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function yearSelect($yearSelect)
    {
        $this->tester->click('#schoolyear');
        $this->tester->wait(2);
        $this->tester->selectOption('#years', $yearSelect);
        $this->tester->click('.btn-primary');
    }

}
