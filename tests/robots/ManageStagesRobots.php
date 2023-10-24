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
     * Página de adicionar etapas.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function pageUpdateStage ()
    {
        $this->tester->amOnPage('?r=stages/default/default/update&id=10056');
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
    /**
     * Observar o nome da etapa.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function seeName ($name)
    {
        $this->tester->seeInField('#stageName', $name);
    }
    /**
     * Pesquisar o nome da etapa.
     * @author Gustavo Santos Oliveira <ti.gustavo@ipti.org.br>
     */
    public function setSearchValue ($value)
    {
        $this->tester->executeJS("document.querySelector('.dataTables_filter input').click();");
        $this->tester->fillField('.dataTables_filter input', $value);
    }

    public function clickUpdate ()
    {
        $this->tester->executeJS("document.querySelector('.stageUpdate').click();");
    }

    public function clickDelete ()
    {
        $this->tester->executeJS("document.querySelector('.stageDelete').click();");
    }

    public function checkUpdate ($name,$stage,$alias)
    {
        $this->tester->seeInField('#stageName', $name);
        $this->tester->seeInField('#stage', $stage);
        $this->tester->seeInField('#stageAlias', $alias);
    }

    public function acceptPopUp ()
    {
        $this->tester->acceptPopup();
    }
}
