<?php

class ClassContentsRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Caso de teste.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function updateSucess()
    {
        $this->tester->see('Aulas ministradas atualizadas com sucesso!');
        $this->tester->wait(1);
    }

    /**
     * Url da página de aulas ministradas.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function page()
    {
        $this->tester->amOnPage('?r=classes/classContents');
    }

    public function btnSave()
    {
        $this->tester->click('.show--desktop');
    }

    /**
     * Seleciona a turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classroom($classroom)
    {
        $selectFieldSelector = '#classroom';

        $this->tester->executeJS("
            var selectField = document.querySelector('$selectFieldSelector');
            var valorParaDigitar = '$classroom';

            selectField.value = valorParaDigitar;

            var event = new Event('change');
            selectField.dispatchEvent(event);
        ");

        $this->tester->wait(1);
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
     * Selecione o componente curricular/eixo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>remo
     */
    public function component($component)
    {
        $this->tester->selectOption('#disciplines', $component);
    }
}
