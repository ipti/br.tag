<?php

class StagesRobots
{
    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }
    /**
     * Página de adicionar etapas.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function pageAddStage ()
    {
        $this->tester->amOnPage('?r=stages/default/create');
    }

    /**
     * Botão para adicionar etapa.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function btnCriar ()
    {
        $this->tester->executeJS("document.querySelector('#saveStage').click();");
    }

    /**
     * Nome da etapa.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function name ($name)
    {
        $this->tester->fillField('#stageName', $name);
    }

    /**
     * Etapa.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function stage ($stage)
    {
        $this->tester->fillField('#stage', $stage);
    }

    /**
     * Abreviação da etapa.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function alias ($alias)
    {
        $this->tester->fillField('#stageAlias', $alias);
    }
}
